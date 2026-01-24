<?php

namespace App\Filament\Resources\ToolTypes;

use App\Filament\Resources\ToolTypes\Pages\CreateToolType;
use App\Filament\Resources\ToolTypes\Pages\EditToolType;
use App\Filament\Resources\ToolTypes\Pages\ListToolTypes;
use App\Filament\Resources\ToolTypes\Schemas\ToolTypeForm;
use App\Filament\Resources\ToolTypes\Tables\ToolTypesTable;
use App\Models\ToolType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ToolTypeResource extends Resource
{
    protected static ?string $model = ToolType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::WrenchScrewdriver;
    protected static UnitEnum|string|null $navigationGroup = 'Datos Generales';
    protected static string|null $navigationLabel = 'Tipos de Herramientas';
    protected static ?string $recordTitleAttribute = 'Tipo';
    protected static ?string $pluralLabel = 'Tipos';
    protected static ?string $modelLabel = 'Tipo';

    public static function form(Schema $schema): Schema
    {
        return ToolTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ToolTypesTable::configure($table);
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
            'index' => ListToolTypes::route('/'),
            'create' => CreateToolType::route('/create'),
            'edit' => EditToolType::route('/{record}/edit'),
        ];
    }
}
