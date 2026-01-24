<?php

namespace App\Filament\Resources\ResourceStatuses;

use App\Filament\Resources\ResourceStatuses\Pages\CreateResourceStatus;
use App\Filament\Resources\ResourceStatuses\Pages\EditResourceStatus;
use App\Filament\Resources\ResourceStatuses\Pages\ListResourceStatuses;
use App\Filament\Resources\ResourceStatuses\Schemas\ResourceStatusForm;
use App\Filament\Resources\ResourceStatuses\Tables\ResourceStatusesTable;
use App\Models\ResourceStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ResourceStatusResource extends Resource
{
    protected static ?string $model = ResourceStatus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CheckBadge;
    protected static UnitEnum|string|null $navigationGroup = 'Datos Generales';
    protected static string|null $navigationLabel = 'Estados de Recursos';
    protected static ?string $recordTitleAttribute = 'estado de recurso';
    protected static ?string $pluralLabel = 'Estados de Recursos';
    protected static ?string $modelLabel = 'Estado de Recurso';

    public static function form(Schema $schema): Schema
    {
        return ResourceStatusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResourceStatusesTable::configure($table);
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
            'index' => ListResourceStatuses::route('/'),
            'create' => CreateResourceStatus::route('/create'),
            'edit' => EditResourceStatus::route('/{record}/edit'),
        ];
    }
}
