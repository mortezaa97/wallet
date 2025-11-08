<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Mortezaa97\Wallet\Models\Wallet;
use Mortezaa97\Wallet\Models\Charge;
use Mortezaa97\Wallet\Models\Withdraw;
use Mortezaa97\Wallet\Policies\WalletPolicy;
use Mortezaa97\Wallet\Policies\ChargePolicy;
use Mortezaa97\Wallet\Policies\WithdrawPolicy;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register policies
        Gate::policy(Wallet::class, WalletPolicy::class);
        Gate::policy(Charge::class, ChargePolicy::class);
        Gate::policy(Withdraw::class, WithdrawPolicy::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('wallet.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'wallet');
    }
}
