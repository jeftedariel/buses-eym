<?php

namespace App\Filament\Resources\Tools\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignments';

    protected static ?string $title = 'Historial de Asignaciones';

    protected static ?string $recordTitleAttribute = 'employee.name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('assigned_at')
                    ->label('Fecha Asignación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('returned_at')
                    ->label('Fecha Devolución')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('En uso')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'info')
                    ->formatStateUsing(fn ($state) => $state ? $state->format('d/m/Y H:i') : 'Activa'),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Duración')
                    ->state(function ($record) {
                        $end = $record->returned_at ?? now();
                        return $record->assigned_at->diffForHumans($end, true);
                    }),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notas')
                    ->limit(50)
                    ->toggleable()
                    ->wrap(),
            ])
            ->defaultSort('assigned_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->label('Solo Activas')
                    ->query(fn ($query) => $query->whereNull('returned_at')),
            ])
            ->headerActions([]);
    }

}
