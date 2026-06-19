@extends('pdf.layout')

@section('title', 'Ficha del vehículo')

@section('content')
    @include('pdf.partials._masthead', ['vehicle' => $vehicle])

    @include('pdf.partials._stats', ['vehicle' => $vehicle])

    <div class="section">
        <div class="section-label">Especificaciones</div>
        @php
            $rows = array_filter([
                'Tipo' => $vehicle->model->type->name,
                'Color' => $vehicle->color,
                'Placa' => $vehicle->license_plate,
                'Cilindrada' => $vehicle->motor_displacement ? $vehicle->motor_displacement . ' L' : null,
                'Capacidad' => $vehicle->capacity ? $vehicle->capacity . ' pasajeros' : null,
                'Año' => $vehicle->model->year,
            ], fn ($v) => filled($v));
        @endphp
        @include('pdf.partials._spec_table', ['rows' => $rows])
    </div>

    @include('pdf.partials._gallery', ['images' => $vehicle->images ?? []])
@endsection
