<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Vehículo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #0ea5e9;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header img {
            max-width: 200px;
            height: auto;
            margin-bottom: 15px;
        }

        .header h1 {
            color: #0ea5e9;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .vehicle-title {
            background-color: #0ea5e9;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .vehicle-title h2 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .vehicle-title p {
            font-size: 16px;
            opacity: 0.95;
        }

        .gallery {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .gallery-item {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #0ea5e9;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            padding: 12px;
            font-weight: bold;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            width: 40%;
        }

        .info-value {
            display: table-cell;
            padding: 12px;
            border: 1px solid #e2e8f0;
        }

        .features-box {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .features-box h3 {
            color: #0ea5e9;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .features-list {
            list-style: none;
            padding: 0;
        }

        .features-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .features-list li:last-child {
            border-bottom: none;
        }

        .features-list li:before {
            color: #0ea5e9;
            font-weight: bold;
            margin-right: 10px;
            font-size: 16px;
        }

        ul {
            list-style-type: none;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .footer img {
            max-width: 120px;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Buses E&M">
        <h1>FICHA DEL VEHÍCULO</h1>
        <p>Generado el {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="vehicle-title">
        <h2>{{ $vehicle->model->manufacturer->name }} {{ $vehicle->model->name }}</h2>
        <p>Año {{ $vehicle->model->year }}</p>
    </div>

    <!-- Información General -->
    <div class="section">
        <div class="section-title">Información General</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Fabricante</div>
                <div class="info-value">{{ $vehicle->model->manufacturer->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Modelo</div>
                <div class="info-value">{{ $vehicle->model->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Año</div>
                <div class="info-value">{{ $vehicle->model->year }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tipo</div>
                <div class="info-value">{{ $vehicle->model->type->name }}</div>
            </div>
        </div>
    </div>

    <!-- Especificaciones Técnicas -->
    <div class="section">
        <div class="section-title">Especificaciones</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Capacidad de Asientos</div>
                <div class="info-value">{{ $vehicle->capacity }} pasajeros</div>
            </div>
            <div class="info-row">
                <div class="info-label">Transmisión</div>
                <div class="info-value">{{ $vehicle->transmission }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Motor</div>
                <div class="info-value">{{ $vehicle->motor_displacement }} Litros</div>
            </div>
            <div class="info-row">
                <div class="info-label">Color</div>
                <div class="info-value">{{ $vehicle->color }}</div>
            </div>
        </div>
    </div>

    <!-- Características -->
    <div class="features-box">
        <h3>Características Destacadas</h3>
        <ul class="features-list">
            <li>Capacidad para {{ $vehicle->capacity }} pasajeros</li>
            <li>Transmisión {{ $vehicle->transmission }}</li>
            <li>Motor de {{ $vehicle->motor_displacement }}L para un rendimiento óptimo</li>
            <li>Vehículo {{ $vehicle->model->type->name }} del año {{ $vehicle->model->year }}</li>
            <li>Mantenimiento regular y revisiones periódicas</li>
            <li>Comodidad y seguridad garantizadas</li>
        </ul>
    </div>


        <!-- Galería de Imágenes (cuadrícula 4x por fila) -->
    @if($vehicle->images && count($vehicle->images) > 0)
    <div class="section gallery" style="padding-top:8px;">
        <div class="section-title"> Galería de Imágenes</div>

        {{-- Usamos una tabla para asegurar filas de 4 imágenes en PDFs --}}
        <table style="width:100%; border-collapse:collapse; margin-top:12px;">
            <tbody>
                @foreach(collect($vehicle->images)->chunk(4) as $chunk)
                <tr>
                    @foreach($chunk as $image)
                    <td style="padding:8px; width:25%; vertical-align:top;">
                        <div style=" solid #e2e8f0; overflow:hidden; background:#fff; display:flex; align-items:center; justify-content:center; height:140px;">
                            @php($imageSrc = \App\Support\PdfImage::dataUri($image))
                            @if($imageSrc)
                            <img src="{{ $imageSrc }}" alt="Imagen del vehículo" style="display:block; max-width:100%; max-height:140px; width:auto; height:auto; object-fit:contain;">
                            @endif
                        </div>
                    </td>
                    @endforeach

                    {{-- Rellenar celdas vacías si la fila tiene menos de 4 --}}
                    @if($chunk->count() < 4)
                        @for($i = 0; $i < 4 - $chunk->count(); $i++)
                            <td style="padding:8px; width:25%;"></td>
                        @endfor
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

        @if(count($vehicle->images) > 12)
        <p style="margin-top: 10px; font-size: 12px; color: #666; text-align: center;">
            Mostrando todas las imágenes (Total: {{ count($vehicle->images) }})
        </p>
        @endif
    </div>
    @endif


    <div class="footer">
        <img src="{{ public_path('images/logo.png') }}" alt="Buses E&M">
        <p style="margin-top: 5px;">Documento generado el {{ now()->format('d/m/Y') }} - <a href="https://boltbitcr.com" target="_blank" rel="noopener noreferrer">Boltbit</a></p>
    </div>
</body>
</html>
