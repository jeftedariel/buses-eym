<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PdfImage
{
    /**
     * Devuelve una imagen del almacenamiento como data URI base64,
     * lista para embeber en un <img> de un PDF (dompdf).
     *
     * Funciona con cualquier disco (local "public" o "s3"/R2), leyendo
     * desde el disco por defecto configurado. Cachea el resultado para
     * no descargar la misma imagen en cada generación de PDF.
     */
    public static function dataUri(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $disk = config('filesystems.default');

        return Cache::remember(
            "pdf-image:{$disk}:{$path}",
            now()->addHours(6),
            function () use ($disk, $path): ?string {
                try {
                    $storage = Storage::disk($disk);

                    if (! $storage->exists($path)) {
                        return null;
                    }

                    $contents = $storage->get($path);

                    if ($contents === null) {
                        return null;
                    }

                    $mime = $storage->mimeType($path) ?: 'image/jpeg';

                    return 'data:' . $mime . ';base64,' . base64_encode($contents);
                } catch (Throwable) {
                    return null;
                }
            }
        );
    }
}
