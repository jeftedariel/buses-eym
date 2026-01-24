<?php

namespace App\Filament\Resources\ToolTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ToolTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('description')
                    ->label('Descripción'),
                Select::make('manufacturer_id')
                    ->label('Fabricante')
                    ->relationship('manufacturer', 'name')
                    ->required(),
                Select::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }
}
