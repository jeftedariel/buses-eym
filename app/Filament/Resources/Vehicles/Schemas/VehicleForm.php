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
                    ->relationship(
                        name: 'model',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->with(['manufacturer', 'type'])
                    )
                    ->label('Modelo')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getSearchResultsUsing(function (string $search) {
                        return \App\Models\VehicleModel::query()
                            ->with(['manufacturer', 'type'])
                            ->where(function ($query) use ($search) {
                                $query->where('name', 'like', "%{$search}%")
                                    ->orWhere('year', 'like', "%{$search}%")
                                    ->orWhereHas('manufacturer', function ($q) use ($search) {
                                        $q->where('name', 'like', "%{$search}%");
                                    });
                            })
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($record) => [
                                $record->id => "{$record->manufacturer->name} - {$record->name} - {$record->year}"
                            ]);
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) =>
                        "{$record->manufacturer->name} - {$record->name} - {$record->year}"
                    )
                    ->createOptionForm([
                        Select::make('manufacturer_id')
                            ->relationship('manufacturer', 'name')
                            ->label('Fabricante')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required(),
                            ]),
                        Select::make('type_id')
                            ->relationship('type', 'name')
                            ->label('Tipo')
                            ->required()
                            ->preload()
                            ->searchable()
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
                    ->label('Imágenes')
                    ->image()
                    ->imageEditor()
                    ->multiple()
                    ->optimize('webp')
                    ->resize(50)
                    ->visibility('public')
                    ->directory('vehicles'),
                Textarea::make('description')
                    ->columnSpanFull(),

            ]);
    }
}
