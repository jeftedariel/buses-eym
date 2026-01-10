<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Vehículo - Detallada</title>
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

        .header .badge {
            background-color: #ef4444;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
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
            margin-bottom: 25px;
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
            padding: 10px;
            font-weight: bold;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            width: 35%;
        }

        .info-value {
            display: table-cell;
            padding: 10px;
            border: 1px solid #e2e8f0;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: bold;
        }

        .status-disponible {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-en-uso {
            background-color: #fed7aa;
            color: #9a3412;
        }

        .status-mantenimiento {
            background-color: #cffafe;
            color: #164e63;
        }

        .status-danada {
            background-color: #fecaca;
            color: #991b1b;
        }

        .status-fuera-servicio {
            background-color: #f1f5f9;
            color: #475569;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .history-table th {
            background-color: #f1f5f9;
            padding: 10px;
            text-align: left;
            border: 1px solid #e2e8f0;
            font-size: 13px;
        }

        .history-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            font-size: 12px;
        }

        .history-table tr:nth-child(even) {
            background-color: #fafafa;
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

        .confidential {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Buses E&M">
        <h1>FICHA DE VEHÍCULO - DETALLADA</h1>
        <p style="margin-top: 5px;">Generado el {{ now()->format('d/m/Y H:i') }}</p>
    </div>


    <!-- Información del Modelo -->
    <div class="section">
        <div class="section-title">Información del Modelo</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Fabricante</div>
                <div class="info-value">{{ $vehicle->model->manufacturer->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tipo de Vehículo</div>
                <div class="info-value">{{ $vehicle->model->type->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Modelo</div>
                <div class="info-value">{{ $vehicle->model->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Año</div>
                <div class="info-value">{{ $vehicle->model->year }}</div>
            </div>
        </div>
    </div>

    <!-- Especificaciones del Vehículo -->
    <div class="section">
        <div class="section-title">Especificaciones</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Placa</div>
                <div class="info-value">{{ $vehicle->license_plate ?? 'No asignada' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Color</div>
                <div class="info-value">{{ $vehicle->color }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Capacidad de Asientos</div>
                <div class="info-value">{{ $vehicle->capacity }} asientos</div>
            </div>
            <div class="info-row">
                <div class="info-label">Transmisión</div>
                <div class="info-value">{{ $vehicle->transmission }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Cilindrada del Motor</div>
                <div class="info-value">{{ $vehicle->motor_displacement }} L</div>
            </div>
            @if($vehicle->description)
            <div class="info-row">
                <div class="info-label">Notas Internas</div>
                <div class="info-value">{{ $vehicle->description }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Estado Actual -->
    <div class="section">
        <div class="section-title">Estado Actual</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Estado</div>
                <div class="info-value">
                    @php
                        $statusName = $vehicle->latestStatusHistory?->status?->name ?? 'Sin estado';
                        $statusClass = match($statusName) {
                            'Disponible' => 'status-disponible',
                            'En Uso' => 'status-en-uso',
                            'En Mantenimiento' => 'status-mantenimiento',
                            'Dañada' => 'status-danada',
                            'Fuera de Servicio' => 'status-fuera-servicio',
                            default => 'status-fuera-servicio',
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusName }}</span>
                </div>
            </div>
            @if($vehicle->latestStatusHistory)
            <div class="info-row">
                <div class="info-label">Último Cambio</div>
                <div class="info-value">{{ $vehicle->latestStatusHistory->created_at->format('d/m/Y H:i') }}</div>
            </div>
            @if($vehicle->latestStatusHistory->description)
            <div class="info-row">
                <div class="info-label">Descripción del Estado</div>
                <div class="info-value">{{ $vehicle->latestStatusHistory->description }}</div>
            </div>
            @endif
            @endif
        </div>
    </div>

    <!-- Historial de Estados -->
    @if($vehicle->statusHistories->count() > 0)
    <div class="section">
        <div class="section-title">Historial Completo de Estados</div>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Estado</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicle->statusHistories->sortByDesc('created_at')->take(15) as $history)
                <tr>
                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @php
                            $statusName = $history->status->name;
                            $statusClass = match($statusName) {
                                'Disponible' => 'status-disponible',
                                'En Uso' => 'status-en-uso',
                                'En Mantenimiento' => 'status-mantenimiento',
                                'Dañada' => 'status-danada',
                                'Fuera de Servicio' => 'status-fuera-servicio',
                                default => 'status-fuera-servicio',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $statusName }}</span>
                    </td>
                    <td>{{ $history->description ?? 'Sin descripción' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($vehicle->statusHistories->count() > 15)
        <p style="margin-top: 10px; font-size: 12px; color: #666;">
            Mostrando los últimos 15 cambios de estado. Total: {{ $vehicle->statusHistories->count() }}
        </p>
        @endif
    </div>
    @endif

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
                            <img src="{{ storage_path('app/public/' . $image) }}" alt="Imagen del vehículo" style="display:block; max-width:100%; max-height:140px; width:auto; height:auto; object-fit:contain;">
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
