<?php

namespace App\Filament\Widgets;

use App\Models\Supply;
use App\Models\Vehicle;
use App\Models\Tool;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InventoryStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalSupplies = Supply::count();
        $totalVehicles = Vehicle::count();
        $totalTools = Tool::count();
        $outOfStockSupplies = Supply::where('quantity', 0)->count();
        $lowStockSupplies = Supply::where('quantity', '>', 0)->where('quantity', '<', 10)->count();

        return [
            Stat::make('Total Suplementos', $totalSupplies)
                ->description('En el inventario')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->chart([7, 12, 8, 15, $totalSupplies]),

            Stat::make('Total Vehículos', $totalVehicles)
                ->description('En el inventario')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info')
                ->chart([5, 8, 6, 10, $totalVehicles]),

            Stat::make('Total Herramientas', $totalTools)
                ->description('En el inventario')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('success')
                ->chart([10, 15, 12, 18, $totalTools]),

            Stat::make('Sin Stock', $outOfStockSupplies)
                ->description('Suplementos agotados')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart([$outOfStockSupplies, $outOfStockSupplies + 2, $outOfStockSupplies]),

            Stat::make('Stock Bajo', $lowStockSupplies)
                ->description('Suplementos < 10 unidades')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning')
                ->chart([$lowStockSupplies + 3, $lowStockSupplies + 1, $lowStockSupplies]),
        ];
    }
}
