<?php

namespace App\Filament\Resources\Supplies\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WithdrawalHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'withdrawalHistories';
    protected static ?string $title = 'Historial de Retiros';

    public function table(Table $table): Table
    {
       return $table
            ->recordTitleAttribute('quantity')
            ->columns([
                TextColumn::make('withdrawn_at')
                    ->label('Fecha y Hora de Retiro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Cantidad Retirada')
                    ->numeric()
                    ->badge()
                    ->sortable()
                    ->color('warning'),
                TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Notas')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
            ])
            ->filters([
                SelectFilter::make('employee_id')
                    ->label('Empleado')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload(),
                Filter::make('withdrawal_at')
                    ->form([
                        DatePicker::make('from')
                            ->label('Desde'),
                        DatePicker::make('until')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('withdrawn_at', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('withdrawn_at', '<=', $data['until']));
                    }),
            ])
            ->headerActions([

            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
