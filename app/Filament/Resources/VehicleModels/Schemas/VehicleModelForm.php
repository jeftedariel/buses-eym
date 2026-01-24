<?php

namespace App\Filament\Resources\VehicleModels\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('year')
                    ->label('Año')
                    ->required()
                    ->numeric(),
                Select::make('manufacturer_id')
                    ->label('Fabricante')
                    ->relationship('manufacturer', 'name')
                    ->required(),
                Select::make('type_id')
                    ->label('Tipo')
                    ->relationship('type', 'name')
                    ->required(),
            ]);
    }
}
