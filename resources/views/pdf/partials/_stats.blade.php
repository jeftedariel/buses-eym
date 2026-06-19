{{-- Tres datos clave --}}
<table class="stats">
    <tr>
        <td>
            <div class="num"><span>{{ $vehicle->capacity }}</span></div>
            <div class="lbl">Asientos</div>
        </td>
        <td class="div">
            <div class="num">{{ $vehicle->transmission }}</div>
            <div class="lbl">Transmisión</div>
        </td>
        <td class="div">
            <div class="num"><span>{{ $vehicle->motor_displacement }}L</span></div>
            <div class="lbl">Motor</div>
        </td>
    </tr>
</table>
