{{-- Specs a dos columnas. Recibe $rows como array [etiqueta => valor]. --}}
@php($cols = collect($rows)->chunk((int) ceil(max(count($rows), 1) / 2)))
<table style="width: 100%; border-collapse: collapse;">
    <tr>
        @foreach($cols as $i => $col)
        <td style="width: 50%; vertical-align: top; {{ $i === 0 ? 'padding-right: 22px;' : 'padding-left: 22px;' }}">
            <table class="spec">
                @foreach($col as $label => $value)
                <tr>
                    <td class="k">{{ $label }}</td>
                    <td class="v">{{ $value }}</td>
                </tr>
                @endforeach
            </table>
        </td>
        @endforeach
    </tr>
</table>
