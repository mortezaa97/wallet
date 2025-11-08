<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Charges\Pages;

use Mortezaa97\Wallet\Filament\Resources\Charges\ChargeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCharge extends EditRecord
{
    protected static string $resource = ChargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

