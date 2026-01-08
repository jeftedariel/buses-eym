<?php

namespace App\Filament\Resources\Tools\Schemas;

use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ToolInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Header Principal
                Section::make()
                    ->schema([
                        Group::make([
                            TextEntry::make('code')
                                ->label('Código')
                                ->weight('bold')
                                ->color('primary')
                                ->icon('heroicon-o-hashtag')
                                ->copyable()
                                ->copyMessage('Código copiado')
                                ->copyMessageDuration(1500),

                            TextEntry::make('latestStatusHistory.status.name')
                                ->label('Estado Actual')
                                ->badge()
                                ->color(fn ($record) => match($record->latestStatusHistory?->status?->name) {
                                    'Disponible' => 'success',
                                    'En Uso' => 'warning',
                                    'En Mantenimiento' => 'info',
                                    'Dañada' => 'danger',
                                    'Fuera de Servicio' => 'gray',
                                    default => 'gray',
                                })
                                ->icon(fn ($record) => match($record->latestStatusHistory?->status?->name) {
                                    'Disponible' => 'heroicon-o-check-circle',
                                    'En Uso' => 'heroicon-o-clock',
                                    'En Mantenimiento' => 'heroicon-o-wrench',
                                    'Dañada' => 'heroicon-o-exclamation-triangle',
                                    'Fuera de Servicio' => 'heroicon-o-x-circle',
                                    default => 'heroicon-o-question-mark-circle',
                                })
                                ->default('Sin estado'),

                            TextEntry::make('latestStatusHistory.created_at')
                                ->label('Último Cambio')
                                ->dateTime('d M Y, H:i')
                                ->icon('heroicon-m-clock')
                                ->color('gray')
                                ->badge()
                                ->since()
                                ->tooltip(fn ($record) => $record->latestStatusHistory?->created_at?->format('l, d \d\e F \d\e Y \a \l\a\s H:i:s')),
                        ])->columns(3),
                    ])
                    ->columns(3)
                    ->columnSpan(2),

                // Descripción
                Section::make('Descripción')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextEntry::make('description')
                            ->label('')
                            ->placeholder('Sin descripción disponible')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(fn ($record) => empty($record->description)),

                // Clasificación
                Group::make([
                    Section::make('Tipo de Herramienta')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->schema([
                            TextEntry::make('type.name')
                                ->label('')
                                ->badge()
                                ->color('primary')
                                ->icon('heroicon-m-wrench')
                                ->default('Sin tipo'),
                        ])
                        ->compact(),

                    Section::make('Fabricante')
                        ->icon('heroicon-o-building-office-2')
                        ->schema([
                            TextEntry::make('type.manufacturer.name')
                                ->label('')
                                ->badge()
                                ->color('info')
                                ->icon('heroicon-m-building-storefront')
                                ->default('Sin fabricante'),
                        ])
                        ->compact(),

                    Section::make('Categoría')
                        ->icon('heroicon-o-rectangle-stack')
                        ->schema([
                            TextEntry::make('type.category.name')
                                ->label('')
                                ->badge()
                                ->color('warning')
                                ->icon('heroicon-m-tag')
                                ->default('Sin categoría'),
                        ])
                        ->compact(),
                ])->columns(3),

                // Información del Sistema
                Section::make('Registro del Sistema')
                    ->icon('heroicon-o-information-circle')
                    ->description('Información de registro y modificación')
                    ->schema([
                        Group::make([
                            TextEntry::make('created_at')
                                ->label('Creado')
                                ->dateTime('d M Y, H:i')
                                ->icon('heroicon-m-plus-circle')
                                ->color('success')
                                ->badge()
                                ->since()
                                ->tooltip(fn ($record) => $record->created_at?->format('l, d \d\e F \d\e Y \a \l\a\s H:i:s')),

                            TextEntry::make('updated_at')
                                ->label('Actualizado')
                                ->dateTime('d M Y, H:i')
                                ->icon('heroicon-m-arrow-path')
                                ->color('gray')
                                ->badge()
                                ->since()
                                ->tooltip(fn ($record) => $record->updated_at?->format('l, d \d\e F \d\e Y \a \l\a\s H:i:s')),
                        ])->columns(2),
                    ])
                    ->collapsed()
                    ->collapsible(),
            ])
            ->columns(2);
    }
}
