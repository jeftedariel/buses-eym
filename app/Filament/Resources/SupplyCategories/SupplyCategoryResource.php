<?php

namespace App\Filament\Resources\SupplyCategories;

use App\Filament\Resources\SupplyCategories\Pages\CreateSupplyCategory;
use App\Filament\Resources\SupplyCategories\Pages\EditSupplyCategory;
use App\Filament\Resources\SupplyCategories\Pages\ListSupplyCategories;
use App\Filament\Resources\SupplyCategories\Schemas\SupplyCategoryForm;
use App\Filament\Resources\SupplyCategories\Tables\SupplyCategoriesTable;
use App\Models\SupplyCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SupplyCategoryResource extends Resource
{
    protected static ?string $model = SupplyCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Tag;
    protected static UnitEnum|string|null $navigationGroup = 'Datos Generales';
    protected static string|null $navigationLabel = 'Categorías de Suplementos';
    protected static ?string $recordTitleAttribute = 'Categoría';
    protected static ?string $pluralLabel = 'Categorías';
    protected static ?string $modelLabel = 'Categoría';

    public static function form(Schema $schema): Schema
    {
        return SupplyCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplyCategoriesTable::configure($table);
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
            'index' => ListSupplyCategories::route('/'),
            'create' => CreateSupplyCategory::route('/create'),
            'edit' => EditSupplyCategory::route('/{record}/edit'),
        ];
    }
}
