<?php

namespace App\Filament\Resources\Tools\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ToolsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('type.name')
                    ->label('Tipo de Herramienta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type.manufacturer.name')
                    ->label('Fabricante')
                    ->sortable(),
                TextColumn::make('type.category.name')
                    ->label('Categoría')
                    ->sortable(),
                TextColumn::make('latestStatusHistory.status.name')
                    ->label('Estado Actual')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->color(fn ($record) => match($record->latestStatusHistory?->status?->name) {
                                    'Disponible' => 'success',
                                    'En Uso' => 'warning',
                                    'En Mantenimiento' => 'info',
                                    'Dañada' => 'danger',
                                    'Fuera de Servicio' => 'gray',
                                    default => 'gray',
                                })
                    ->icon(fn ($record) => match($record->latestStatusHistory?->status?->name) {
                                    'Disponible' => 'heroicon-o-check-circle',
                                    'En Uso' => 'heroicon-o-clock',
                                    'En Mantenimiento' => 'heroicon-o-wrench',
                                    'Dañada' => 'heroicon-o-exclamation-triangle',
                                    'Fuera de Servicio' => 'heroicon-o-x-circle',
                                    default => 'heroicon-o-question-mark-circle',
                                }),

                TextColumn::make('latestStatusHistory.created_at')
                    ->label('Último Cambio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Fecha de Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
