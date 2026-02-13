<?php

namespace App\Filament\Resources\SupplyWithdrawalHistories;

use App\Filament\Resources\SupplyWithdrawalHistories\Pages\CreateSupplyWithdrawalHistory;
use App\Filament\Resources\SupplyWithdrawalHistories\Pages\EditSupplyWithdrawalHistory;
use App\Filament\Resources\SupplyWithdrawalHistories\Pages\ListSupplyWithdrawalHistories;
use App\Filament\Resources\SupplyWithdrawalHistories\Schemas\SupplyWithdrawalHistoryForm;
use App\Filament\Resources\SupplyWithdrawalHistories\Tables\SupplyWithdrawalHistoriesTable;
use App\Models\SupplyWithdrawalHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class SupplyWithdrawalHistoryResource extends Resource
{
    protected static ?string $model = SupplyWithdrawalHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Clock;
    protected static UnitEnum|string|null $navigationGroup = 'Historiales';
    protected static string|null $navigationLabel = 'Movimientos de Suplementos';
    protected static ?string $recordTitleAttribute = 'Historial de Retiro';
    protected static ?string $pluralLabel = 'Retiros de Suplemento';
    protected static ?string $modelLabel = 'Retiro de Suplemento';


    public static function form(Schema $schema): Schema
    {
        return SupplyWithdrawalHistoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplyWithdrawalHistoriesTable::configure($table);
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
            'index' => ListSupplyWithdrawalHistories::route('/'),
            'create' => CreateSupplyWithdrawalHistory::route('/create'),
            'edit' => EditSupplyWithdrawalHistory::route('/{record}/edit'),
        ];
    }



}
