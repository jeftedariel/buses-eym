<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('capacity')
                    ->numeric(),
                TextEntry::make('transmission'),
                TextEntry::make('motor_displacement'),
                TextEntry::make('color'),
                TextEntry::make('license_plate')
                    ->placeholder('-'),
                TextEntry::make('images')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('available')
                    ->boolean()
                    ->placeholder('-'),
                IconEntry::make('displayable')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('model.name')
                    ->label('Model'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
