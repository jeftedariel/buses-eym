{{-- Título editorial sin imagen. $statusBadge es HTML opcional. --}}
<div class="masthead">
    <div class="kicker">{{ $vehicle->model->type->name }} &middot; {{ $vehicle->model->year }}</div>
    <table style="width: 100%;">
        <tr>
            <td style="vertical-align: bottom;">
                <div class="headline">{{ $vehicle->model->manufacturer->name }} {{ $vehicle->model->name }}</div>
            </td>
            @isset($statusBadge)
            <td style="text-align: right; vertical-align: bottom;">{!! $statusBadge !!}</td>
            @endisset
        </tr>
    </table>
    <div class="rule"></div>
</div>
