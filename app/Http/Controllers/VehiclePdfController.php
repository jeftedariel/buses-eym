<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;

class VehiclePdfController extends Controller
{
    public function downloadDetailed(Vehicle $vehicle)
    {
        $vehicle->load(['model.manufacturer', 'model.type', 'statusHistories.status']);

        $pdf = Pdf::loadView('pdf.vehicle-detailed', [
            'vehicle' => $vehicle,
        ]);

        $filename = 'vehiculo_detallado_' . $this->sanitizeFilename($vehicle->license_plate ?? $vehicle->id) . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadClient(Vehicle $vehicle)
    {
        $vehicle->load(['model.manufacturer', 'model.type']);

        $pdf = Pdf::loadView('pdf.vehicle-client', [
            'vehicle' => $vehicle,
        ]);

        $filename = 'vehiculo_' . $this->sanitizeFilename($vehicle->model->name ?? $vehicle->id) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Sanitize filename by removing invalid characters
     */
    private function sanitizeFilename(string $filename): string
    {
        // Remove / and \ characters and other problematic characters
        $filename = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $filename);

        // Remove multiple consecutive underscores
        $filename = preg_replace('/_+/', '_', $filename);

        // Trim underscores from start and end
        return trim($filename, '_');
    }
}
