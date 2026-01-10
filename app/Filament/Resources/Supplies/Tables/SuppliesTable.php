<?php

namespace App\Filament\Resources\Supplies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;

class SuppliesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nombre'),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state < 10 => 'warning',
                        $state < 50 => 'info',
                        default => 'success',
                    }),
                TextColumn::make('manufacturer.name')
                    ->label('Fabricante')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('manufacturer_id')
                    ->label('Fabricante')
                    ->relationship('manufacturer', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->preload()
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('decrease')
                    ->label('Retirar Existencias')
                    ->icon('heroicon-m-minus-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Retirar Existencias')
                    ->modalDescription(fn ($record) => "Stock actual: {$record->quantity} unidades")
                    ->modalIcon('heroicon-o-minus-circle')
                    ->form([
                        TextInput::make('quantity_to_decrease')
                            ->label('Cantidad a retirar')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(fn ($record) => $record->quantity)
                            ->helperText(fn ($record) => "Máximo disponible: {$record->quantity} unidades")
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $record) {
                                if ($state > $record->quantity) {
                                    $set('quantity_to_decrease', $record->quantity);
                                }
                            }),
                    ])
                    ->action(function ($record, array $data) {
                        $newQuantity = $record->quantity - $data['quantity_to_decrease'];

                        $record->update([
                            'quantity' => $newQuantity,
                        ]);

                        Notification::make()
                            ->title('Existencias actualizadas')
                            ->body("Se disminuyeron {$data['quantity_to_decrease']} unidades. Stock actual: {$newQuantity}")
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->quantity > 0),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
