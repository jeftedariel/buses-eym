<?php

namespace App\Filament\Resources\Supplies;

use App\Filament\Resources\Supplies\Pages\CreateSupply;
use App\Filament\Resources\Supplies\Pages\EditSupply;
use App\Filament\Resources\Supplies\Pages\ListSupplies;
use App\Filament\Resources\Supplies\Pages\ViewSupply;
use App\Filament\Resources\Supplies\RelationManagers\WithdrawalHistoriesRelationManager;
use App\Filament\Resources\Supplies\Schemas\SupplyForm;
use App\Filament\Resources\Supplies\Schemas\SupplyInfolist;
use App\Filament\Resources\Supplies\Tables\SuppliesTable;
use App\Models\Supply;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use UnitEnum;

class SupplyResource extends Resource
{
    protected static ?string $model = Supply::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cube;
    protected static UnitEnum|string|null $navigationGroup = 'Inventario';
    protected static string|null $navigationLabel = 'Suplementos';
    protected static ?string $recordTitleAttribute = 'suplemento';
    protected static ?string $pluralLabel = 'Suplementos';
    protected static ?string $modelLabel = 'Suplemento';

    public static function form(Schema $schema): Schema
    {
        return SupplyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SupplyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuppliesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            WithdrawalHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupplies::route('/'),
            'create' => CreateSupply::route('/create'),
            'view' => ViewSupply::route('/{record}'),
            'edit' => EditSupply::route('/{record}/edit'),
        ];
    }
}
