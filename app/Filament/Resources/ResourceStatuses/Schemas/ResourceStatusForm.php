<?php

namespace App\Filament\Resources\ResourceStatuses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ResourceStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Nombre'),
                TextInput::make('description')
                    ->label('Descripción'),
            ]);
    }
}
