<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Withdraws\Pages;

use Mortezaa97\Wallet\Filament\Resources\Withdraws\WithdrawResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditWithdraw extends EditRecord
{
    protected static string $resource = WithdrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

