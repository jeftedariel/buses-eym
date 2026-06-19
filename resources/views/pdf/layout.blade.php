<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Ficha de vehículo')</title>
    <style>
        @page { margin: 32px 36px; }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.45;
        }

        a { color: #0ea5e9; text-decoration: none; }

        /* ---------- Header ---------- */
        .doc-header {
            width: 100%;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .doc-header td { vertical-align: middle; }
        .doc-header .logo img { width: 150px; height: auto; }
        .doc-header .doc-title {
            text-align: right;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: bold;
            color: #64748b;
        }

        /* ---------- Hero ---------- */
        .hero-img { width: 100%; }
        .hero-img img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
        }
        .hero-band {
            width: 100%;
            background-color: #0f172a;
            border-radius: 8px;
            margin-top: 10px;
        }
        .hero-band td { padding: 16px 20px; vertical-align: middle; }
        .hero-band .make-model { color: #ffffff; font-size: 22px; font-weight: bold; }
        .hero-band .sub { color: #94a3b8; font-size: 13px; margin-top: 3px; }

        /* ---------- Stats ---------- */
        .stats { width: 100%; border-collapse: collapse; margin: 18px 0; }
        .stats td {
            width: 33.33%;
            text-align: center;
            padding: 14px 8px;
            border: 1px solid #e5e7eb;
        }
        .stats .num { font-size: 19px; font-weight: bold; color: #0ea5e9; }
        .stats .lbl {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-top: 5px;
        }

        /* ---------- Sections ---------- */
        .section { margin-bottom: 22px; page-break-inside: avoid; }
        .section-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 2px solid #0ea5e9;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        /* ---------- Spec table ---------- */
        .spec { width: 100%; border-collapse: collapse; }
        .spec td { padding: 9px 4px; border-bottom: 1px solid #e5e7eb; font-size: 12px; }
        .spec td.k { color: #64748b; width: 40%; }
        .spec td.v { color: #0f172a; font-weight: bold; }

        /* ---------- History table ---------- */
        .history { width: 100%; border-collapse: collapse; }
        .history th {
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            padding: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        .history td {
            padding: 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
            vertical-align: top;
        }

        /* ---------- Status badges ---------- */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-disponible { background-color: #dcfce7; color: #166534; }
        .status-en-uso { background-color: #fed7aa; color: #9a3412; }
        .status-mantenimiento { background-color: #cffafe; color: #164e63; }
        .status-danada { background-color: #fecaca; color: #991b1b; }
        .status-fuera-servicio { background-color: #f1f5f9; color: #475569; }

        /* ---------- Gallery ---------- */
        .gallery { width: 100%; border-collapse: collapse; }
        .gallery td {
            padding: 6px;
            width: 25%;
            height: 130px;
            text-align: center;
            vertical-align: middle;
        }
        .gallery img {
            max-width: 100%;
            max-height: 120px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }

        /* ---------- Footer ---------- */
        .footer {
            margin-top: 30px;
            padding-top: 14px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #64748b;
            font-size: 10px;
        }
        .footer img { width: 90px; height: auto; margin-bottom: 6px; }
    </style>
</head>
<body>
    <table class="doc-header">
        <tr>
            <td class="logo"><img src="{{ public_path('images/logo.png') }}" alt="Buses E&M"></td>
            <td class="doc-title">@yield('title', 'Ficha de vehículo')</td>
        </tr>
    </table>

    @yield('content')

    <div class="footer">
        <img src="{{ public_path('images/logo.png') }}" alt="Buses E&M">
        <div>Documento generado el {{ now()->format('d/m/Y') }} &mdash; <a href="https://boltbitcr.com" target="_blank" rel="noopener noreferrer">Boltbit</a></div>
    </div>
</body>
</html>
