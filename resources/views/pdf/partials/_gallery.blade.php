{{-- Galería en tabla, 4 por fila (a prueba de DOMPDF). Recibe $images. --}}
@if($images && count($images) > 0)
<div class="section">
    <div class="section-label">Galería de imágenes</div>
    <table class="gallery">
        @foreach(collect($images)->chunk(4) as $chunk)
        <tr>
            @foreach($chunk as $image)
            @php($src = \App\Support\PdfImage::dataUri($image))
            <td>
                @if($src)
                <img src="{{ $src }}" alt="Imagen del vehículo">
                @endif
            </td>
            @endforeach

            {{-- Rellenar celdas vacías si la fila tiene menos de 4 --}}
            @if($chunk->count() < 4)
                @for($i = 0; $i < 4 - $chunk->count(); $i++)
                <td></td>
                @endfor
            @endif
        </tr>
        @endforeach
    </table>
</div>
@endif
