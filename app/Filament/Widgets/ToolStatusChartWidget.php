<?php

namespace App\Filament\Widgets;

use App\Models\Tool;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ToolStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 14;

    public function getHeading(): ?string
    {
        return 'Estado de Herramientas';
    }

    protected function getData(): array
    {
        $statusCounts = Tool::query()
            ->join('tool_status_histories', function($join) {
                $join->on('tools.id', '=', 'tool_status_histories.tool_id')
                    ->whereRaw('tool_status_histories.id = (
                        SELECT id FROM tool_status_histories tsh2
                        WHERE tsh2.tool_id = tools.id
                        ORDER BY tsh2.created_at DESC
                        LIMIT 1
                    )');
            })
            ->join('resource_statuses', 'tool_status_histories.status_id', '=', 'resource_statuses.id')
            ->select('resource_statuses.name', DB::raw('count(*) as total'))
            ->groupBy('resource_statuses.name')
            ->get();

        $toolsWithoutStatus = Tool::query()
            ->whereDoesntHave('statusHistories')
            ->count();

        $labels = $statusCounts->pluck('name')->toArray();
        $data = $statusCounts->pluck('total')->toArray();

        if ($toolsWithoutStatus > 0) {
            $labels[] = 'Sin Estado';
            $data[] = $toolsWithoutStatus;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Herramientas',
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
