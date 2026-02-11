<?php

namespace App\Filament\Resources\Vehicles\Tables;

use App\Filament\Resources\Tools\RelationManagers\StatusHistoriesRelationManager;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Models\VehicleStatusHistory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;

class VehiclesTable
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
                    ->imageGallery()
                    ->toggleable(),
                TextColumn::make('model.manufacturer.name')
                    ->label('Fabricante')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('model.type.name')
                    ->label('Tipo')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('model.name')
                    ->label('Modelo')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('model.year')
                    ->label('Año')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('capacity')
                    ->label('Capacidad Asientos')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('transmission')
                    ->label('Transmisión')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('motor_displacement')
                    ->label('Motor (L)')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('color')
                    ->label('Color')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('license_plate')
                    ->label('Placa')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
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
                                })
                    ->toggleable(),

                TextColumn::make('latestStatusHistory.created_at')
                    ->label('Último Cambio de Estado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
                        VehicleStatusHistory::select('status_id')
                            ->distinct()
                            ->with('status')
                            ->get()
                            ->pluck('status.name', 'status.id')
                            ->toArray()
                    ),
                SelectFilter::make('model.manufacturer_id')
                    ->label('Fabricante')
                    ->relationship('model.manufacturer', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('model.type_id')
                    ->label('Tipo')
                    ->relationship('model.type', 'name')
                    ->preload()
                    ->searchable(),
                SelectFilter::make('model_year')
                    ->label('Año')
                    ->query(fn ($query, $data) => (
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereHas('model', fn ($q) => $q->where('year', $data['value']))
                            : $query
                    ))
                    ->options(
                        VehicleModel::select('year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->toArray()
                    )
                    ->preload()
                    ->searchable(),
                SelectFilter::make('model_name')
                    ->label('Modelo')
                    ->query(fn ($query, $data) => (
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->whereHas('model', fn ($q) => $q->where('name', $data['value']))
                            : $query
                    ))
                    ->options(
                        VehicleModel::select('name')
                            ->distinct()
                            ->orderBy('name')
                            ->pluck('name', 'name')
                            ->toArray()
                    )
                    ->preload()
                    ->searchable(),
                SelectFilter::make('transmission')
                    ->label('Transmisión')
                    ->preload()
                    ->searchable()
                    ->query(fn ($query, $data) => (
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->where('transmission', $data['value'])
                            : $query
                    ))
                    ->options([
                        Vehicle::select('transmission')->distinct()->pluck('transmission', 'transmission')->toArray()
                    ]),
                SelectFilter::make('capacity')
                    ->label('Capacidad Asientos')
                    ->preload()
                    ->searchable()
                    ->query(fn ($query, $data) => (
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->where('capacity', $data['value'])
                            : $query
                    ))
                    ->options([
                        Vehicle::select('capacity')->distinct()->pluck('capacity', 'capacity')->toArray()
                    ]),
                SelectFilter::make('motor_displacement')
                    ->label('Motor (L)')
                    ->preload()
                    ->searchable()
                    ->query(fn ($query, $data) => (
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->where('motor_displacement', $data['value'])
                            : $query
                    ))
                    ->options([
                        Vehicle::select('motor_displacement')->distinct()->pluck('motor_displacement', 'motor_displacement')->toArray()
                    ]),
                SelectFilter::make('color')
                    ->label('Color')
                    ->preload()
                    ->searchable()
                    ->query(fn ($query, $data) => (
                        isset($data['value']) && $data['value'] !== ''
                            ? $query->where('color', $data['value'])
                            : $query
                    ))
                    ->options([
                        Vehicle::select('color')->distinct()->pluck('color', 'color')->toArray()
                    ]),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordActions([
                ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                RelationManagerAction::make('statusHistories')
                    ->label('Historial de Estados')
                    ->icon('heroicon-o-archive-box')
                    ->relationManager(StatusHistoriesRelationManager::class),
                ActionGroup::make([
                    Action::make('downloadDetailedPdf')
                    ->label('PDF Detallado')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn ($record) => route('vehicles.pdf.detailed', $record))
                    ->openUrlInNewTab(),
                    Action::make('downloadClientPdf')
                    ->label('PDF Cliente')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn ($record) => route('vehicles.pdf.client', $record))
                    ->openUrlInNewTab(),
                    ])

    ->label('Exportar')
    ->icon('heroicon-m-ellipsis-vertical')
    ->color('info')
    ->button()
                ])
                ->hiddenLabel()
                ->button()
                ->color('info')
                ->icon(Heroicon::Wrench)
            ])->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
