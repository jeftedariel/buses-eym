<?php

namespace App\Filament\Widgets;

use App\Models\ToolStatusHistory;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class ToolStatusChangesWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Últimos Cambios de Herramientas')
            ->query(
                ToolStatusHistory::query()
                    ->with(['status', 'tool.type'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('resource_type')
                    ->label('Tipo')
                    ->badge()
                    ->state('Herramienta')
                    ->color('success')
                    ->icon('heroicon-m-wrench-screwdriver'),

                TextColumn::make('tool.type.name')
                    ->label('Recurso')
                    ->formatStateUsing(fn ($record) =>
                        ($record->tool?->type?->name ?? 'N/A') .
                        ' (' . ($record->tool?->code ?? 'Sin código') . ')'
                    )
                    ->searchable()
                    ->weight('bold'),

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
