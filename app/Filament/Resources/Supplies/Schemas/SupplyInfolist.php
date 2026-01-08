<?php

namespace App\Filament\Resources\Supplies\Schemas;

use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SupplyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Sección Principal - Información del Suministro
                Section::make('Información del Suministro')
                    ->description('Detalles principales del suministro')
                    ->icon('heroicon-o-cube')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nombre')
                            ->weight('bold')
                            ->color('primary')
                            ->icon('heroicon-m-tag'),

                        TextEntry::make('quantity')
                            ->label('Cantidad en Stock')
                            ->numeric()
                            ->badge()
                            ->color(fn (?int $state): string => match (true) {
                                $state === null || $state === 0 => 'danger',
                                $state < 10 => 'warning',
                                $state < 50 => 'info',
                                default => 'success',
                            })
                            ->icon(fn (?int $state): string => match (true) {
                                $state === null || $state === 0 => 'heroicon-m-x-circle',
                                $state < 10 => 'heroicon-m-exclamation-triangle',
                                default => 'heroicon-m-check-circle',
                            })
                            ->suffix(' unidades'),

                        TextEntry::make('description')
                            ->label('Descripción')
                            ->placeholder('Sin descripción')
                            ->columnSpanFull()
                            ->icon('heroicon-m-document-text')
                            ->color('gray'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Sección de Clasificación
                Section::make('Clasificación')
                    ->description('Categorización y fabricante')
                    ->icon('heroicon-o-folder')
                    ->schema([
                        TextEntry::make('manufacturer.name')
                            ->label('Fabricante')
                            ->icon('heroicon-m-building-office-2')
                            ->badge()
                            ->color('info')
                            ->weight('semibold'),

                        TextEntry::make('category.name')
                            ->label('Categoría')
                            ->icon('heroicon-m-rectangle-stack')
                            ->badge()
                            ->color('warning')
                            ->weight('semibold'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Sección de Metadatos
                Section::make('Información del Sistema')
                    ->description('Fechas de registro y modificación')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Fecha de Creación')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-m-calendar-days')
                            ->color('success')
                            ->placeholder('No disponible')
                            ->since(),

                        TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-m-arrow-path')
                            ->color('warning')
                            ->placeholder('No disponible')
                            ->since(),
                    ])
                    ->columns(2)
                    ->collapsed()
                    ->collapsible(),
            ]);
    }
}
