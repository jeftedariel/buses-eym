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
