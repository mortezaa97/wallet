<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Wallets\Pages;

use Mortezaa97\Wallet\Filament\Resources\Wallets\WalletResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWallets extends ListRecords
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

