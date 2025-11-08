<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Wallets\Pages;

use Mortezaa97\Wallet\Filament\Resources\Wallets\WalletResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWallet extends CreateRecord
{
    protected static string $resource = WalletResource::class;
}

