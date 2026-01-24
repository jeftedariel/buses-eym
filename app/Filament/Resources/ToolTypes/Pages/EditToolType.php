<?php

namespace App\Filament\Resources\ToolTypes\Pages;

use App\Filament\Resources\ToolTypes\ToolTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditToolType extends EditRecord
{
    protected static string $resource = ToolTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
