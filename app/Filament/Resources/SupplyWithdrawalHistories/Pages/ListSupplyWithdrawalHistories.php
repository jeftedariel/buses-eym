<?php

namespace App\Filament\Resources\SupplyWithdrawalHistories\Pages;

use App\Filament\Resources\SupplyWithdrawalHistories\SupplyWithdrawalHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSupplyWithdrawalHistories extends ListRecords
{
    protected static string $resource = SupplyWithdrawalHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

}
