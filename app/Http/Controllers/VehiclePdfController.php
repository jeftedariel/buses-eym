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

        $filename = 'vehiculo_detallado_' . ($vehicle->license_plate ?? $vehicle->id) . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadClient(Vehicle $vehicle)
    {
        $vehicle->load(['model.manufacturer', 'model.type']);

        $pdf = Pdf::loadView('pdf.vehicle-client', [
            'vehicle' => $vehicle,
        ]);

        $filename = 'vehiculo_' . ($vehicle->model->name ?? $vehicle->id) . '.pdf';

        return $pdf->download($filename);
    }
}
