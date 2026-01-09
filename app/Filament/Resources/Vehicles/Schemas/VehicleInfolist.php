<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;
use Alsaloul\ImageGallery\Infolists\Entries\ImageGalleryEntry;
class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Galería de Imágenes
                Section::make('Galería de Imágenes')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        ImageGalleryEntry::make('images')
                            ->disk(config('filesystems.default'))
                            ->thumbWidth(128)
                            ->thumbHeight(128)
                            ->imageGap('gap-4'),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($record) => empty($record->images)),

                // Información Principal del Vehículo
                Section::make('Información del Vehículo')
                    ->description('Detalles principales y características')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Group::make([
                            TextEntry::make('license_plate')
                                ->label('Placa')
                                ->weight('bold')
                                ->color('primary')
                                ->icon('heroicon-o-identification')
                                ->copyable()
                                ->copyMessage('Placa copiada')
                                ->copyMessageDuration(1500)
                                ->placeholder('Sin placa'),

                            TextEntry::make('color')
                                ->label('Color')
                                ->badge()
                                ->color('gray')
                                ->icon('heroicon-o-paint-brush')
                                ->placeholder('Sin especificar'),

                            TextEntry::make('transmission')
                                ->label('Transmisión')
                                ->badge()
                                ->color(fn ($state) => match(strtolower($state ?? '')) {
                                    'automática', 'automatic' => 'info',
                                    'manual' => 'warning',
                                    default => 'gray',
                                })
                                ->icon(fn ($state) => match(strtolower($state ?? '')) {
                                    'automática', 'automatic' => 'heroicon-o-cog-6-tooth',
                                    'manual' => 'heroicon-o-wrench-screwdriver',
                                    default => 'heroicon-o-question-mark-circle',
                                })
                                ->placeholder('No especificada'),
                        ])->columns(3),

                        Group::make([
                            TextEntry::make('capacity')
                                ->label('Capacidad de Asientos')
                                ->numeric()
                                ->badge()
                                ->color('success')
                                ->icon('heroicon-o-user-group')
                                ->suffix(' asientos')
                                ->placeholder('No especificada'),

                            TextEntry::make('motor_displacement')
                                ->label('Cilindrada del Motor')
                                ->badge()
                                ->color('warning')
                                ->icon('heroicon-o-bolt')
                                ->suffix(' L')
                                ->placeholder('No especificada'),
                        ])->columns(2),
                    ])
                    ->collapsible(),

                // Modelo y Especificaciones
                Group::make([
                    Section::make('Fabricante')
                        ->icon('heroicon-o-building-office-2')
                        ->schema([
                            TextEntry::make('model.manufacturer.name')
                                ->label('')
                                ->badge()
                                ->color('info')
                                ->icon('heroicon-m-building-storefront')
                                ->default('Sin fabricante'),
                        ]),

                    Section::make('Tipo de Vehículo')
                        ->icon('heroicon-o-cube')
                        ->schema([
                            TextEntry::make('model.type.name')
                                ->label('')
                                ->badge()
                                ->color('primary')
                                ->icon('heroicon-m-truck')
                                ->default('Sin tipo'),
                        ]),

                    Section::make('Modelo')
                        ->icon('heroicon-o-tag')
                        ->schema([
                            TextEntry::make('model.name')
                                ->label('')
                                ->badge()
                                ->color('warning')
                                ->icon('heroicon-m-star')
                                ->default('Sin modelo'),
                        ]),

                    Section::make('Año')
                        ->icon('heroicon-o-calendar')
                        ->schema([
                            TextEntry::make('model.year')
                                ->label('')
                                ->badge()
                                ->color('success')
                                ->icon('heroicon-m-calendar-days')
                                ->default('Sin año'),
                        ]),
                ])->columns(2),

            ]);
    }
}
