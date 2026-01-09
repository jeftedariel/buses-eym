<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Laravel\Pail\File;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('model_id')
                    ->relationship('model', 'name')
                    ->label('Modelo')
                    ->required()
                    ->createOptionForm([
                        Select::make('manufacturer_id')
                            ->relationship('manufacturer', 'name')
                            ->label('Fabricante')
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required(),
                            ]),
                        Select::make('type_id')
                            ->relationship('type', 'name')
                            ->label('Tipo')
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required(),
                            ]),
                        TextInput::make('name')
                            ->label('Modelo')
                            ->required(),
                        TextInput::make('year')
                            ->label('Año')
                            ->required()
                            ->numeric(),
                    ]),
                TextInput::make('capacity')
                    ->label('Capacidad Asientos')
                    ->required()
                    ->numeric(),
                TextInput::make('transmission')
                    ->label('Transmisión')
                    ->required(),
                TextInput::make('motor_displacement')
                    ->label('Motor (L)')
                    ->required()
                    ->numeric(),
                TextInput::make('color')
                    ->label('Color')
                    ->required(),
                TextInput::make('license_plate')
                    ->label('Placa'),
                FileUpload::make('images')
                    ->label('Img')
                    ->image()
                    ->multiple()
                    ->directory('vehicles'),
                Textarea::make('description')
                    ->columnSpanFull(),

            ]);
    }
}
