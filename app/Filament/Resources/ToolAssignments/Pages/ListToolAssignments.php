<?php

namespace App\Filament\Resources\ToolAssignments\Pages;

use App\Filament\Resources\ToolAssignments\ToolAssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListToolAssignments extends ListRecords
{
    protected static string $resource = ToolAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
