<?php

namespace App\Filament\Resources\SupplyWithdrawalHistories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplyWithdrawalHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('notes')
                    ->label('Notas'),
                TextInput::make('quantity')
                    ->label('Cantidad')
                    ->required()
                    ->numeric(),
                Select::make('supply_id')
                    ->label('Suplemento')
                    ->options(function () {
                        return \App\Models\Supply::pluck('name', 'id');
                    })
                    ->required(),
                Select::make('employee_id')
                    ->label('Empleado')
                    ->options(function () {
                        return \App\Models\Employee::pluck('name', 'id');
                    })
                    ->required(),
                DateTimePicker::make('withdrawn_at')
                    ->label('Retiro el')
                    ->required(),
            ]);
    }
}
