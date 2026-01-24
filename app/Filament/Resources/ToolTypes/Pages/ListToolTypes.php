<?php

namespace App\Filament\Resources\ToolTypes\Pages;

use App\Filament\Resources\ToolTypes\ToolTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListToolTypes extends ListRecords
{
    protected static string $resource = ToolTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
