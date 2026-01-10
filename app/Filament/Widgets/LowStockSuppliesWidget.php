<?php

namespace App\Filament\Widgets;

use App\Models\Supply;
use Filament\Actions\Action as ActionsAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;

class LowStockSuppliesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Supply::query()
                    ->where('quantity', '<=', 10)
                    ->orderBy('quantity', 'asc')
                    ->with(['manufacturer', 'category'])
            )
            ->heading('Alertas de Suplementos')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-cube'),

                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->badge()
                    ->sortable()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state < 5 => 'warning',
                        $state < 10 => 'info',
                        default => 'success',
                    })
                    ->icon(fn (int $state): string => match (true) {
                        $state === 0 => 'heroicon-m-x-circle',
                        $state < 5 => 'heroicon-m-exclamation-triangle',
                        default => 'heroicon-m-exclamation-circle',
                    })
                    ->suffix(' unidades'),

                TextColumn::make('manufacturer.name')
                    ->label('Fabricante')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->placeholder('Sin descripción')
                    ->toggleable(),
            ])
            ->actions([
                ActionsAction::make('view')
                    ->label('Ver')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Supply $record): string => route('filament.dashboard.resources.supplies.view', ['record' => $record]))
                    ->openUrlInNewTab(false),
            ])
            ->emptyStateHeading('Todo bien')
            ->emptyStateDescription('No hay suplementos con stock bajo en este momento.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
