<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Mortezaa97\Wallet\Filament\Resources\Wallets\WalletResource;
use Mortezaa97\Wallet\Filament\Resources\Charges\ChargeResource;
use Mortezaa97\Wallet\Filament\Resources\Withdraws\WithdrawResource;

class WalletPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'wallet';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                'WalletResource' => WalletResource::class,
                'ChargeResource' => ChargeResource::class,
                'WithdrawResource' => WithdrawResource::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}

