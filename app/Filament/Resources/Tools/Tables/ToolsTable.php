<?php

namespace App\Filament\Resources\Tools\Tables;

use App\Filament\Resources\Tools\Actions\AssignToolAction;
use App\Filament\Resources\Tools\RelationManagers\AssignmentsRelationManager;
use App\Filament\Resources\Tools\RelationManagers\StatusHistoriesRelationManager;
use App\Models\ToolStatusHistory;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;
use Illuminate\Support\Facades\Gate;

class ToolsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Img')
                    ->imageHeight(35)
                    ->stacked()
                    ->limit(3)
                    ->imageGallery()
                    ->toggleable(),

                TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->limit(50)
                    ->toggleable(),

                TextColumn::make('type.name')
                    ->label('Tipo de Herramienta')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type.manufacturer.name')
                    ->label('Fabricante')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('type.category.name')
                    ->label('Categoría')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('currentAssignment.employee.name')
                    ->label('Asignada a')
                    ->default('Disponible')
                    ->badge()
                    ->color(fn($record) => $record->isAssigned() ? 'info' : 'success')
                    ->icon(fn($record) => $record->isAssigned() ? 'heroicon-o-user' : 'heroicon-o-check-circle')
                    ->description(
                        fn($record) => $record->isAssigned()
                            ? 'Desde: ' . $record->currentAssignment->assigned_at->format('d/m/Y')
                            : null
                    )
                    ->toggleable(),

                TextColumn::make('latestStatusHistory.status.name')
                    ->label('Estado Actual')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->color(fn($record) => match ($record->latestStatusHistory?->status?->name) {
                        'Disponible' => 'success',
                        'En Uso' => 'warning',
                        'En Mantenimiento' => 'info',
                        'Dañada' => 'danger',
                        'Fuera de Servicio' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn($record) => match ($record->latestStatusHistory?->status?->name) {
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
                    ->query(fn($query, $data) => (
                        isset($data['value']) && $data['value'] !== ''
                        ? $query->whereHas('latestStatusHistory.status', fn($q) => $q->where('id', $data['value']))
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

                SelectFilter::make('assignment_status')
                    ->label('Estado de Asignación')
                    ->options([
                        'available' => 'Disponible',
                        'assigned' => 'Asignada',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'assigned') {
                            return $query->whereHas('currentAssignment');
                        } elseif ($data['value'] === 'available') {
                            return $query->whereDoesntHave('currentAssignment');
                        }
                        return $query;
                    }),
            ])
            ->recordActions([

                ActionGroup::make([
                ViewAction::make(),

                EditAction::make(),

                        AssignToolAction::make(),
                        RelationManagerAction::make('statusHistories')
                            ->label('Historial de Estados')
                            ->icon('heroicon-o-archive-box')
                            ->relationManager(StatusHistoriesRelationManager::class)
                            ->visible(fn() => Gate::forUser(Filament::auth()->user())->check('ViewStatusHistory:Tool')),
                        RelationManagerAction::make('assignments')
                            ->label('Ver Historial de Asignaciones')
                            ->icon('heroicon-o-clock')
                            ->relationManager(AssignmentsRelationManager::class)
                            ->visible(fn() => Gate::forUser(Filament::auth()->user())->check('ViewAssignmentHistory:Tool')),

                ])->hiddenLabel()
                ->button()
                ->color('info')
                ->icon(Heroicon::Wrench)
                ])->recordActionsPosition(RecordActionsPosition::BeforeColumns)

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
