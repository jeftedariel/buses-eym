<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Ficha de vehículo')</title>
    <style>
        @page { margin: 28px 34px; }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #0f172a;
            font-size: 11px;
            line-height: 1.4;
        }

        a { color: #0ea5e9; text-decoration: none; }

        /* ---------- Header ---------- */
        .doc-header {
            width: 100%;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .doc-header td { vertical-align: middle; }
        .doc-header .logo img { width: 130px; height: auto; }
        .doc-header .doc-title {
            text-align: right;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: bold;
            color: #94a3b8;
        }

        /* ---------- Masthead (sin imagen) ---------- */
        .masthead { margin-bottom: 18px; }
        .kicker {
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #0ea5e9;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .headline {
            font-size: 30px;
            font-weight: bold;
            color: #0f172a;
            line-height: 1.05;
        }
        .masthead .rule {
            height: 2px;
            background-color: #0f172a;
            margin-top: 12px;
        }

        /* ---------- Stats ---------- */
        .stats {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 22px;
        }
        .stats td { width: 33.33%; text-align: center; padding: 15px 8px; }
        .stats .div { border-left: 1px solid #e5e7eb; }
        .stats .num { font-size: 18px; font-weight: bold; color: #0f172a; }
        .stats .num span { color: #0ea5e9; }
        .stats .lbl {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-top: 5px;
        }

        /* ---------- Sections ---------- */
        .section { margin-bottom: 20px; }
        .section-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 2px solid #0ea5e9;
            padding-bottom: 5px;
            margin-bottom: 12px;
        }

        /* ---------- Spec table (dos columnas) ---------- */
        .spec { width: 100%; border-collapse: collapse; }
        .spec td { padding: 8px 2px; border-bottom: 1px solid #eef2f6; font-size: 11px; }
        .spec td.k { color: #64748b; width: 45%; }
        .spec td.v { color: #0f172a; font-weight: bold; text-align: right; }

        /* ---------- History table ---------- */
        .history { width: 100%; border-collapse: collapse; }
        .history th {
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            padding: 7px;
            border-bottom: 2px solid #e5e7eb;
        }
        .history td {
            padding: 7px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 10px;
            vertical-align: top;
        }

        /* ---------- Status badges ---------- */
        .badge {
            display: inline-block;
            padding: 3px 11px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-disponible { background-color: #dcfce7; color: #166534; }
        .status-en-uso { background-color: #fed7aa; color: #9a3412; }
        .status-mantenimiento { background-color: #cffafe; color: #164e63; }
        .status-danada { background-color: #fecaca; color: #991b1b; }
        .status-fuera-servicio { background-color: #f1f5f9; color: #475569; }

        /* ---------- Gallery (única fuente de fotos) ---------- */
        .gallery { width: 100%; border-collapse: collapse; }
        .gallery td {
            padding: 5px;
            width: 25%;
            height: 100px;
            text-align: center;
            vertical-align: middle;
        }
        .gallery img {
            max-width: 100%;
            max-height: 92px;
            border-radius: 6px;
        }

        /* ---------- Footer ---------- */
        .footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #94a3b8;
            font-size: 9px;
        }
        .footer img { width: 80px; height: auto; margin-bottom: 5px; }
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
