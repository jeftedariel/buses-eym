<?php

namespace App\Filament\Resources\SupplyWithdrawalHistories\Pages;

use App\Filament\Resources\SupplyWithdrawalHistories\SupplyWithdrawalHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSupplyWithdrawalHistory extends EditRecord
{
    protected static string $resource = SupplyWithdrawalHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
