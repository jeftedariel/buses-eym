# Docker Deployment — Buses EYM

Dockerized stack for the Laravel + Filament admin panel. The app container
serves HTTP on **port 7070** (plain HTTP); place the host Caddy in front of it
to terminate TLS.

## Stack

| Service      | Image                   | Exposed to host        | Purpose                         |
|--------------|-------------------------|------------------------|---------------------------------|
| `app`        | built from `Dockerfile` | `7070` (HTTP)          | Nginx + PHP-FPM 8.4 + queue     |
| `postgres`   | `postgres:16-alpine`    | —                      | Application database            |
| `minio`      | `minio/minio`           | `9000` API, `9001` UI  | S3-compatible object storage    |
| `minio-init` | `minio/mc`              | —                      | Creates bucket on first boot    |

Volumes: `db_data`, `minio_data`, `app_storage`. Network: `internal`.

## Quick start

```bash
# 1. Prepare env
cp .env.docker .env     # edit passwords & APP_KEY

# 2. Generate APP_KEY (one-off)
docker compose run --rm app php artisan key:generate --show
# Paste the returned base64: string into APP_KEY in .env

# 3. Build & boot
docker compose up -d --build

# 4. Tail the app logs until "Startup complete"
docker compose logs -f app

# 5. Smoke-test
curl -v http://localhost:7070/up
```

Admin panel: <http://localhost:7070/dashboard>
MinIO console: <http://localhost:9001> (login with `MINIO_ROOT_USER` / `MINIO_ROOT_PASSWORD`)

## Environment variables

All configuration lives in `.env` (created from `.env.docker`). The most
important ones:

| Var                     | Default                      | Notes                              |
|-------------------------|------------------------------|------------------------------------|
| `APP_KEY`               | *(empty — must set)*         | Generated via `artisan key:generate` |
| `APP_URL`               | `http://localhost:7070`      | Public URL behind Caddy            |
| `POSTGRES_*`            | `buses_eym` / secret         | DB is created automatically        |
| `MINIO_ROOT_USER/PASS`  | `minioadmin` / secret        | Also the S3 access/secret keys     |
| `MINIO_BUCKET`          | `uploads`                    | Auto-created by `minio-init`       |
| `AWS_URL`               | `http://localhost:9000/uploads` | Public URL used in asset links |
| `RUN_SEEDERS`           | `false`                      | Set `true` once to seed DB         |

Inside the `app` container:
- `FILESYSTEM_DISK=s3`
- `AWS_ENDPOINT=http://minio:9000`
- `AWS_USE_PATH_STYLE_ENDPOINT=true`

## Placing Caddy in front

The app listens on `0.0.0.0:7070`. A minimal Caddyfile on the host:

```caddy
app.example.com {
    encode zstd gzip
    reverse_proxy 127.0.0.1:7070
}
```

Caddy terminates TLS and forwards over HTTP; Nginx in the container already
honours `X-Forwarded-Proto` / `X-Forwarded-For`, so Laravel will emit correct
HTTPS URLs. If the host runs Docker on a different interface, swap
`127.0.0.1:7070` for that bind address.

## Serving uploads publicly

The `minio-init` service applies the `download` anonymous policy to the bucket
— files uploaded to the bucket are readable by URL. Proxy them through Caddy
for TLS + stable hostname:

```caddy
files.example.com {
    reverse_proxy 127.0.0.1:9000
}
```

Then set `AWS_URL=https://files.example.com/uploads` in `.env` and restart
the `app` service.

## Common operations

```bash
# Tail all logs
docker compose logs -f

# Shell into the app
docker compose exec app bash

# Run artisan
docker compose exec app php artisan <command>

# Run migrations explicitly
docker compose exec app php artisan migrate --force

# Run seeders
docker compose exec app php artisan db:seed --force

# Connect to Postgres
docker compose exec postgres psql -U buses_eym -d buses_eym

# Rebuild from scratch
docker compose down
docker compose up -d --build

# Wipe data (DESTRUCTIVE — deletes DB and uploads)
docker compose down -v
```

## Troubleshooting

- **App returns 502 / health check failing**: check
  `docker compose logs app` — the entrypoint prints migration output and
  config-cache errors. Config typos surface there first.
- **`/up` works but `/dashboard` redirects to login loop**: make sure
  `APP_URL` matches the public URL Caddy serves (including scheme).
- **Uploads saved but 403 on read**: confirm `minio-init` ran —
  `docker compose logs minio-init`. Re-run with
  `docker compose run --rm minio-init`.
- **`APP_KEY` empty warning**: generate one and set it in `.env`, then
  `docker compose up -d app`.
- **Queue jobs not running**: the container ships a single
  `php artisan queue:work` supervisor worker. For heavier workloads, scale
  by editing `docker/supervisor/supervisord.conf` (`numprocs`).

## File layout

```
Dockerfile                    # Multi-stage: vendor -> assets -> runtime
docker-compose.yml            # App, Postgres 16, MinIO, mc init
.dockerignore
.env.docker                   # Template for .env
docker/
  entrypoint.sh               # Waits for DB, migrates, caches, execs CMD
  nginx/
    nginx.conf                # Main nginx config (stdout logs)
    app.conf                  # vhost on :7070 -> PHP-FPM
  php/
    php.ini                   # Opcache + limits
    www.conf                  # PHP-FPM pool (listens on 127.0.0.1:9000)
  supervisor/
    supervisord.conf          # Runs nginx + php-fpm + queue worker
```
