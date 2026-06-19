{{-- Tabla de specs. Recibe $rows como array [etiqueta => valor]. --}}
<table class="spec">
    @foreach($rows as $label => $value)
    <tr>
        <td class="k">{{ $label }}</td>
        <td class="v">{{ $value }}</td>
    </tr>
    @endforeach
</table>
