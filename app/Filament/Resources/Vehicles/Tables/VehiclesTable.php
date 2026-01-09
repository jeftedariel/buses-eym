<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Img')
                    ->imageHeight(30)
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->imageGallery(),
                TextColumn::make('model.manufacturer.name')
                    ->label('Fabricante')
                    ->searchable(),
                TextColumn::make('model.type.name')
                    ->label('Tipo')
                    ->searchable(),
                TextColumn::make('model.name')
                    ->label('Modelo')
                    ->searchable(),
                TextColumn::make('model.year')
                    ->label('Año')
                    ->searchable(),
                TextColumn::make('capacity')
                    ->label('Capacidad Asientos')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('transmission')
                    ->label('Transmisión')
                    ->searchable(),
                TextColumn::make('motor_displacement')
                    ->label('Motor (L)')
                    ->searchable(),
                TextColumn::make('color')
                    ->label('Color')
                    ->searchable(),
                TextColumn::make('license_plate')
                    ->label('Placa')
                    ->searchable(),
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
                    ->label('Último Cambio de Estado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
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
            ]);
    }
}
