@extends('pdf.layout')

@section('title', 'Ficha del vehículo')

@section('content')
    @include('pdf.partials._hero', ['vehicle' => $vehicle])

    @include('pdf.partials._stats', ['vehicle' => $vehicle])

    <div class="section">
        <div class="section-label">Especificaciones</div>
        @php
            $rows = array_filter([
                'Tipo' => $vehicle->model->type->name,
                'Color' => $vehicle->color,
                'Placa' => $vehicle->license_plate,
                'Cilindrada del motor' => $vehicle->motor_displacement ? $vehicle->motor_displacement . ' L' : null,
                'Capacidad' => $vehicle->capacity ? $vehicle->capacity . ' pasajeros' : null,
            ], fn ($v) => filled($v));
        @endphp
        @include('pdf.partials._spec_table', ['rows' => $rows])
    </div>

    {{-- La primera imagen ya se usó en el hero --}}
    @include('pdf.partials._gallery', ['images' => collect($vehicle->images ?? [])->skip(1)->values()])
@endsection
