<?php

namespace App\Filament\Resources\ResourceStatuses\Pages;

use App\Filament\Resources\ResourceStatuses\ResourceStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResourceStatus extends EditRecord
{
    protected static string $resource = ResourceStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
