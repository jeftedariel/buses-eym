<?php

namespace App\Filament\Resources\Supplies\Schemas;

use Dom\Text;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Código'),

                TextInput::make('name')
                    ->required()
                    ->label('Nombre'),
                TextInput::make('description')
                    ->label('Descripción'),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label('Cantidad'),
                Select::make('manufacturer_id')
                    ->label('Fabricante')
                    ->relationship('manufacturer', 'name')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre'),
                    ]),
                Select::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre'),
                        TextInput::make('description')
                            ->label('Descripción'),
                    ]),
                FileUpload::make('images')
                            ->label('Imágenes')
                            ->image()
                            ->multiple()
                            ->imageEditor()
                            ->optimize('webp')
                            ->resize(50)
                            ->directory('supplies'),
            ]);
    }
}
