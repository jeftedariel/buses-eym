{{-- Foto principal (primera imagen) + banda de título. $statusBadge es HTML opcional. --}}
@php
    $heroSrc = null;
    if ($vehicle->images && count($vehicle->images) > 0) {
        $heroSrc = \App\Support\PdfImage::dataUri($vehicle->images[0]);
    }
@endphp

@if($heroSrc)
<div class="hero-img"><img src="{{ $heroSrc }}" alt="{{ $vehicle->model->name }}"></div>
@endif

<table class="hero-band">
    <tr>
        <td>
            <div class="make-model">{{ $vehicle->model->manufacturer->name }} {{ $vehicle->model->name }}</div>
            <div class="sub">Año {{ $vehicle->model->year }} &middot; {{ $vehicle->model->type->name }}</div>
        </td>
        @isset($statusBadge)
        <td style="text-align: right;">{!! $statusBadge !!}</td>
        @endisset
    </tr>
</table>
