<?php

namespace App\Filament\Resources\SupplyCategories\Pages;

use App\Filament\Resources\SupplyCategories\SupplyCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupplyCategories extends ListRecords
{
    protected static string $resource = SupplyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
