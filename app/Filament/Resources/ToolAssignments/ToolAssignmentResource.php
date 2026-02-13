<?php

namespace App\Filament\Resources\ToolAssignments;

use App\Filament\Resources\ToolAssignments\Pages\CreateToolAssignment;
use App\Filament\Resources\ToolAssignments\Pages\EditToolAssignment;
use App\Filament\Resources\ToolAssignments\Pages\ListToolAssignments;
use App\Filament\Resources\ToolAssignments\Schemas\ToolAssignmentForm;
use App\Filament\Resources\ToolAssignments\Tables\ToolAssignmentsTable;
use App\Models\ToolAssignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ToolAssignmentResource extends Resource
{
    protected static ?string $model = ToolAssignment::class;

        protected static string|BackedEnum|null $navigationIcon = Heroicon::Clock;
    protected static UnitEnum|string|null $navigationGroup = 'Historiales';
    protected static string|null $navigationLabel = 'Movimientos de Herramientas';
    protected static ?string $recordTitleAttribute = 'Historial de Retiro';
    protected static ?string $pluralLabel = 'Retiros de Herramientas';
    protected static ?string $modelLabel = 'Retiro de Herramienta';

    public static function form(Schema $schema): Schema
    {
        return ToolAssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ToolAssignmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListToolAssignments::route('/'),
            'create' => CreateToolAssignment::route('/create'),
            'edit' => EditToolAssignment::route('/{record}/edit'),
        ];
    }
}
