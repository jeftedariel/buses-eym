<?php

namespace App\Filament\Resources\SupplyWithdrawalHistories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SupplyWithdrawalHistoriesTable
{


    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('supply.images')
                    ->label('Img')
                    ->imageHeight(35)
                    ->stacked()
                    ->limit(3)
                    ->imageGallery()
                    ->toggleable(),
                TextColumn::make('supply.code')
                    ->label('Cód')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-tag')
                    ->toggleable(),
                TextColumn::make('supply.name')
                    ->label('Suplemento')
                    ->icon('heroicon-o-archive-box')
                    ->numeric()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->numeric()
                    ->icon('heroicon-o-user')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Notas')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('withdrawn_at')
                    ->label('Retiro el')
                    ->dateTime()
                    ->badge('success')
                    ->color('success')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(fn ($record) => route('filament.dashboard.resources.supplies.view', $record->supply_id))
            ->filters([
                //
            ])
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
