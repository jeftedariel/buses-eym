@extends('pdf.layout')

@section('title', 'Ficha de vehículo · Detallada')

@section('content')
    @php
        $statusName = $vehicle->latestStatusHistory?->status?->name ?? 'Sin estado';
        $statusClass = match ($statusName) {
            'Disponible' => 'status-disponible',
            'En Uso' => 'status-en-uso',
            'En Mantenimiento' => 'status-mantenimiento',
            'Dañada' => 'status-danada',
            'Fuera de Servicio' => 'status-fuera-servicio',
            default => 'status-fuera-servicio',
        };
        $statusBadge = '<span class="badge ' . $statusClass . '">' . e($statusName) . '</span>';
    @endphp

    @include('pdf.partials._masthead', ['vehicle' => $vehicle, 'statusBadge' => $statusBadge])

    @include('pdf.partials._stats', ['vehicle' => $vehicle])

    <div class="section">
        <div class="section-label">Especificaciones</div>
        @php
            $rows = array_filter([
                'Modelo' => $vehicle->model->name,
                'Tipo' => $vehicle->model->type->name,
                'Placa' => $vehicle->license_plate,
                'Color' => $vehicle->color,
                'Cilindrada' => $vehicle->motor_displacement ? $vehicle->motor_displacement . ' L' : null,
                'Capacidad' => $vehicle->capacity ? $vehicle->capacity . ' asientos' : null,
            ], fn ($v) => filled($v));
        @endphp
        @include('pdf.partials._spec_table', ['rows' => $rows])
    </div>

    @if($vehicle->description)
    <div class="section">
        <div class="section-label">Notas internas</div>
        <p style="font-size: 11px; color: #334155;">{{ $vehicle->description }}</p>
    </div>
    @endif

    @if($vehicle->statusHistories->count() > 0)
    <div class="section">
        <div class="section-label">Historial de estados</div>
        <table class="history">
            <thead>
                <tr>
                    <th>Fecha y hora</th>
                    <th>Estado</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vehicle->statusHistories->sortByDesc('created_at')->take(8) as $history)
                @php
                    $hName = $history->status->name;
                    $hClass = match ($hName) {
                        'Disponible' => 'status-disponible',
                        'En Uso' => 'status-en-uso',
                        'En Mantenimiento' => 'status-mantenimiento',
                        'Dañada' => 'status-danada',
                        'Fuera de Servicio' => 'status-fuera-servicio',
                        default => 'status-fuera-servicio',
                    };
                @endphp
                <tr>
                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                    <td><span class="badge {{ $hClass }}">{{ $hName }}</span></td>
                    <td>{{ $history->description ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($vehicle->statusHistories->count() > 8)
        <p style="margin-top: 8px; font-size: 9px; color: #94a3b8;">
            Mostrando los últimos 8 cambios. Total: {{ $vehicle->statusHistories->count() }}
        </p>
        @endif
    </div>
    @endif

    @include('pdf.partials._gallery', ['images' => $vehicle->images ?? []])
@endsection
