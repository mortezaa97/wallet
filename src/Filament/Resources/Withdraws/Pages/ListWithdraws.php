<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Withdraws\Pages;

use Mortezaa97\Wallet\Filament\Resources\Withdraws\WithdrawResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWithdraws extends ListRecords
{
    protected static string $resource = WithdrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

