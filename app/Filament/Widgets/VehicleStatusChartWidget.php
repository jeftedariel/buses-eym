<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VehicleStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 13;

    public function getHeading(): ?string
    {
        return 'Estado de Vehículos';
    }

    protected function getData(): array
    {
        $statusCounts = Vehicle::query()
            ->join('vehicle_status_histories', function($join) {
                $join->on('vehicles.id', '=', 'vehicle_status_histories.vehicle_id')
                    ->whereRaw('vehicle_status_histories.id = (
                        SELECT id FROM vehicle_status_histories vsh2
                        WHERE vsh2.vehicle_id = vehicles.id
                        ORDER BY vsh2.created_at DESC
                        LIMIT 1
                    )');
            })
            ->join('resource_statuses', 'vehicle_status_histories.status_id', '=', 'resource_statuses.id')
            ->select('resource_statuses.name', DB::raw('count(*) as total'))
            ->groupBy('resource_statuses.name')
            ->get();

        $vehiclesWithoutStatus = Vehicle::query()
            ->whereDoesntHave('statusHistories')
            ->count();

        $labels = $statusCounts->pluck('name')->toArray();
        $data = $statusCounts->pluck('total')->toArray();

        if ($vehiclesWithoutStatus > 0) {
            $labels[] = 'Sin Estado';
            $data[] = $vehiclesWithoutStatus;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Vehículos',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',
                        'rgb(251, 146, 60)',
                        'rgb(6, 182, 212)',
                        'rgb(244, 63, 94)',
                        'rgb(156, 163, 175)',
                        'rgb(100, 116, 139)',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
