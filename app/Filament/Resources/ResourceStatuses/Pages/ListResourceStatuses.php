<?php

namespace App\Filament\Resources\ResourceStatuses\Pages;

use App\Filament\Resources\ResourceStatuses\ResourceStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResourceStatuses extends ListRecords
{
    protected static string $resource = ResourceStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
