{{-- Tres datos clave en números grandes --}}
<table class="stats">
    <tr>
        <td>
            <div class="num">{{ $vehicle->capacity }}</div>
            <div class="lbl">Asientos</div>
        </td>
        <td>
            <div class="num">{{ $vehicle->transmission }}</div>
            <div class="lbl">Transmisión</div>
        </td>
        <td>
            <div class="num">{{ $vehicle->motor_displacement }}L</div>
            <div class="lbl">Motor</div>
        </td>
    </tr>
</table>
