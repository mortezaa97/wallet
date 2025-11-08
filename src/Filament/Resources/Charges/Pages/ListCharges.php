<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Charges\Pages;

use Mortezaa97\Wallet\Filament\Resources\Charges\ChargeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCharges extends ListRecords
{
    protected static string $resource = ChargeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

