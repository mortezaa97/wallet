<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Charges\Pages;

use Mortezaa97\Wallet\Filament\Resources\Charges\ChargeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCharge extends CreateRecord
{
    protected static string $resource = ChargeResource::class;
}

