<?php

namespace App\Filament\Widgets;

use App\Models\VehicleStatusHistory;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentStatusChangesWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                VehicleStatusHistory::query()
                    ->with(['status', 'vehicle.model'])
                    ->latest()
                    ->limit(5)
            )
            ->heading('Últimos Cambios de Estado - Vehículos')
            ->columns([
                TextColumn::make('vehicle.model.name')
                    ->label('Vehículo')
                    ->formatStateUsing(fn ($record) =>
                        ($record->vehicle?->model?->name ?? 'N/A') .
                        ' (' . ($record->vehicle?->license_plate ?? 'Sin placa') . ')'
                    )
                    ->searchable()
                    ->weight('bold')
                    ->icon('heroicon-m-truck'),

                TextColumn::make('status.name')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($record): string => match ($record->status?->name) {
                        'Disponible' => 'success',
                        'En Uso' => 'warning',
                        'En Mantenimiento' => 'info',
                        'Dañada' => 'danger',
                        'Fuera de Servicio' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn ($record): string => match ($record->status?->name) {
                        'Disponible' => 'heroicon-o-check-circle',
                        'En Uso' => 'heroicon-o-clock',
                        'En Mantenimiento' => 'heroicon-o-wrench',
                        'Dañada' => 'heroicon-o-exclamation-triangle',
                        'Fuera de Servicio' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->placeholder('Sin descripción')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->tooltip(fn ($record) => $record->created_at->format('l, d \d\e F \d\e Y \a \l\a\s H:i:s')),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Sin cambios recientes')
            ->emptyStateDescription('No hay cambios de estado registrados.')
            ->emptyStateIcon('heroicon-o-clock')
            ->paginated(false);
    }
}
