<?php

namespace App\Filament\Resources\SupplyWithdrawalHistories\Tables;

use App\Filament\Resources\Supplies\RelationManagers\WithdrawalHistoriesRelationManager;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;
use Illuminate\Notifications\Notifiable;

class SupplyWithdrawalHistoriesTable
{


    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('supply.images')
                    ->label('Img')
                    ->visibility('public')
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
                    ->icon(Heroicon::Tag)
                    ->toggleable(),
                TextColumn::make('supply.name')
                    ->label('Suplemento')
                    ->icon(Heroicon::ArchiveBox)
                    ->numeric()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->numeric()
                    ->icon(Heroicon::User)
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Notas')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->icon(Heroicon::Cube)
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
            ->recordUrl(fn($record) => route('filament.dashboard.resources.supplies.view', $record->supply_id))
            ->filters([
                //
            ])
            ->recordActions([
                    Action::make('increase')
                        ->hiddenLabel()
                        ->button()
                        ->icon(Heroicon::PlusCircle)
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Agregar Existencias')
                        ->modalDescription(fn($record) => "Stock actual: {$record->Supply->quantity} unidades")
                        ->modalIcon(Heroicon::PlusCircle)
                        ->button()
                        ->form([
                            TextInput::make('quantity_to_increase')
                                ->label('Cantidad a agregar')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->default(fn ($record) => $record->quantity),
                        ])
                        ->action(function ($record, array $data) {
                            $newQuantity = $record->Supply->quantity + $data['quantity_to_increase'];

                            $record->Supply->update([
                                'quantity' => $newQuantity,
                            ]);

                            Notification::make()
                                ->title('Existencias actualizadas')
                                ->body("Se agregaron {$data['quantity_to_increase']} unidades. Stock actual: {$newQuantity}")
                                ->success()
                                ->send();
                        }),

                ])
                ->recordActionsPosition(RecordActionsPosition::BeforeColumns)

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
