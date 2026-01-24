<?php

namespace App\Filament\Resources\SupplyCategories\Pages;

use App\Filament\Resources\SupplyCategories\SupplyCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSupplyCategory extends EditRecord
{
    protected static string $resource = SupplyCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
