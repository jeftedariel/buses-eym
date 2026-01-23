<?php

namespace App\Filament\Resources\Tools\Tables;

use App\Filament\Resources\Tools\RelationManagers\StatusHistoriesRelationManager;
use App\Models\ToolStatusHistory;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;
class ToolsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Img')
                    ->imageHeight(30)
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->imageGallery(),
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('type.name')
                    ->label('Tipo de Herramienta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type.manufacturer.name')
                    ->label('Fabricante')
                    ->sortable(),
                TextColumn::make('type.category.name')
                    ->label('Categoría')
                    ->sortable(),
                TextColumn::make('latestStatusHistory.status.name')
                    ->label('Estado Actual')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->default('-')
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
                                }),

                TextColumn::make('latestStatusHistory.created_at')
                    ->label('Último Cambio de Estado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Fecha de Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type_id')
                    ->label('Tipo de Herramienta')
                    ->relationship('type', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('manufacturer_id')
                    ->label('Fabricante')
                    ->relationship('type.manufacturer', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('type.category', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('status')
                    ->label('Estado')
                    ->preload()
                    ->searchable()
                    ->query(fn ($query, $data) => (
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereHas('latestStatusHistory.status', fn ($q) => $q->where('id', $data['value']))
                            : $query
                    ))
                    ->options(
                        ToolStatusHistory::select('status_id')
                            ->distinct()
                            ->with('status')
                            ->get()
                            ->pluck('status.name', 'status.id')
                            ->toArray()
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                RelationManagerAction::make('statusHistories')
                    ->label('Historial de Estados')
                    ->icon('heroicon-o-archive-box')
                    ->relationManager(StatusHistoriesRelationManager::class),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
