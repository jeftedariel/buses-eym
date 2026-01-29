<?php

namespace App\Filament\Resources\Supplies\Tables;

use App\Filament\Resources\Supplies\RelationManagers\WithdrawalHistoriesRelationManager;
use DateTime;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;

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
                    ->color(fn(int $state): string => match (true) {
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
                RelationManagerAction::make('withdrawalHistories')
                    ->label('Historial de Retiros')
                    ->relationManager(WithdrawalHistoriesRelationManager::class)
                    ->icon('heroicon-o-archive-box'),
                Action::make('increase')
                    ->label('Agregar')
                    ->icon('heroicon-m-plus-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Agregar Existencias')
                    ->modalDescription(fn($record) => "Stock actual: {$record->quantity} unidades")
                    ->modalIcon('heroicon-o-plus-circle')
                    ->button()
                    ->form([
                        TextInput::make('quantity_to_increase')
                            ->label('Cantidad a agregar')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                    ])
                    ->action(function ($record, array $data) {
                        $newQuantity = $record->quantity + $data['quantity_to_increase'];

                        $record->update([
                            'quantity' => $newQuantity,
                        ]);

                        Notification::make()
                            ->title('Existencias actualizadas')
                            ->body("Se agregaron {$data['quantity_to_increase']} unidades. Stock actual: {$newQuantity}")
                            ->success()
                            ->send();
                    }),
                Action::make('decrease')
                    ->label('Retirar')
                    ->icon('heroicon-m-minus-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Retirar Existencias')
                    ->modalDescription(fn($record) => "Stock actual: {$record->quantity} unidades")
                    ->modalIcon('heroicon-o-minus-circle')
                    ->button()
                    ->form([

                        TextInput::make('quantity_to_decrease')
                            ->label('Cantidad a retirar')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(fn($record) => $record->quantity)
                            ->helperText(fn($record) => "Máximo disponible: {$record->quantity} unidades")
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $record) {
                                if ($state > $record->quantity) {
                                    $set('quantity_to_decrease', $record->quantity);
                                }
                            }),
                        \Filament\Forms\Components\Select::make('employee_id')
                            ->label('Empleado')
                            ->options(\App\Models\Employee::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                \Filament\Forms\Components\TextInput::make('name')
                                    ->label('Nombre del Empleado')
                                    ->required(),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                // Handle the creation of the new employee record
                                // This example assumes an 'Employee' model and a 'name' field
                                $employee = \App\Models\Employee::create($data);

                                // Return the primary key of the newly created record
                                return $employee->getKey();
                            }),
                        \Filament\Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->maxLength(500),
                        DateTimePicker::make('withdrawn_at')
                            ->label('Fecha y Hora de Retiro')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false),
                    ])
                    ->action(function ($record, array $data) {
                        $newQuantity = $record->quantity - $data['quantity_to_decrease'];

                        // Actualizar el stock
                        $record->update([
                            'quantity' => $newQuantity,
                        ]);

                        // Registrar en el historial
                        \App\Models\SupplyWithdrawalHistory::create([
                            'supply_id' => $record->id,
                            'quantity' => $data['quantity_to_decrease'],
                            'employee_id' => $data['employee_id'],
                            'notes' => $data['notes'] ?? null,
                            'withdrawn_at' => $data['withdrawn_at'],
                        ]);

                        Notification::make()
                            ->title('Existencias actualizadas')
                            ->body("Se retiraron {$data['quantity_to_decrease']} unidades. Stock actual: {$newQuantity}")
                            ->success()
                            ->send();
                    })
                    ->visible(fn($record) => $record->quantity > 0),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
