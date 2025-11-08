<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Wallets\Pages;

use Mortezaa97\Wallet\Filament\Resources\Wallets\WalletResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditWallet extends EditRecord
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

