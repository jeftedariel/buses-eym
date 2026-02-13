<?php

namespace App\Filament\Resources\ToolAssignments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ToolAssignmentsTable
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
                TextColumn::make('tool.code')
                    ->label('Codigo')
                    ->icon('heroicon-o-tag')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('employee.name')
                    ->label('Empleado')
                    ->icon('heroicon-o-user')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('assigned_at')
                    ->label('Retiró el')
                    ->icon('heroicon-o-calendar')
                    ->badge('primary')
                    ->color('primary')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('returned_at')
                    ->label('Regresó el')
                    ->icon('heroicon-o-calendar')
                    ->badge('success')
                    ->color('success')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Notas')
                    ->searchable()
                    ->markdown()
                    ->formatStateUsing(fn(string $state): string => str_replace("\n", "  \n", $state)),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(fn ($record) => route('filament.dashboard.resources.tools.view', $record->tool_id))
            ->filters([
                //
            ])
            ->recordActions([])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
