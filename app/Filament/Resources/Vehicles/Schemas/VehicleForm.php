<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('capacity')
                    ->required()
                    ->numeric(),
                TextInput::make('transmission')
                    ->required(),
                TextInput::make('motor_displacement')
                    ->required(),
                TextInput::make('color')
                    ->required(),
                TextInput::make('license_plate'),
                Textarea::make('images')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('available'),
                Toggle::make('displayable'),
                Select::make('model_id')
                    ->relationship('model', 'name')
                    ->required(),
            ]);
    }
}
