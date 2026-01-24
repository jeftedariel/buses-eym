<?php

namespace App\Filament\Resources\SupplyCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplyCategoryForm
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
            ]);
    }
}
