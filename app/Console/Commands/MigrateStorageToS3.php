<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateStorageToS3 extends Command
{
    protected $signature = 'storage:migrate-to-s3
        {--from=public : Disco local de origen (public o local)}
        {--to=s3 : Disco de destino}
        {--delete : Borra el archivo local tras subirlo}
        {--overwrite : Sobrescribe archivos que ya existen en el destino}';

    protected $description = 'Migra todos los archivos de un disco local a S3';

    public function handle(): int
    {
        $from = $this->option('from');
        $to = $this->option('to');

        $source = Storage::disk($from);
        $target = Storage::disk($to);

        $files = $source->allFiles();
        $total = count($files);

        if ($total === 0) {
            $this->info("No hay archivos en el disco '{$from}'.");
            return self::SUCCESS;
        }

        $this->info("Migrando {$total} archivos de '{$from}' a '{$to}'...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $migrated = 0;
        $skipped = 0;

        foreach ($files as $path) {
            if (! $this->option('overwrite') && $target->exists($path)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            $stream = $source->readStream($path);
            $target->writeStream($path, $stream);

            if (is_resource($stream)) {
                fclose($stream);
            }

            if ($this->option('delete')) {
                $source->delete($path);
            }

            $migrated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Listo. Migrados: {$migrated}, omitidos (ya existían): {$skipped}.");

        return self::SUCCESS;
    }
}
