<?php

namespace App\Filament\Resources\ToolAssignments\Pages;

use App\Filament\Resources\ToolAssignments\ToolAssignmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditToolAssignment extends EditRecord
{
    protected static string $resource = ToolAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
