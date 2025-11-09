<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Mortezaa97\Wallet\Http\Controllers\ChargeController;
use Mortezaa97\Wallet\Http\Controllers\PayChargeController;
use Mortezaa97\Wallet\Http\Controllers\WalletController;
use Mortezaa97\Wallet\Http\Controllers\WithdrawController;

Route::prefix('api')->middleware(['api','auth:api'])->group(function () {
    Route::get('wallets', [WalletController::class, 'index']);
    
    // withdraws
    Route::get('withdraws', [WithdrawController::class, 'index']);
    Route::post('withdraws', [WithdrawController::class, 'store']);
    Route::get('withdraws/{withdraw}', [WithdrawController::class, 'show']);

    // charges
    Route::get('charges', [ChargeController::class, 'index']);
    Route::get('charges/{charge}', [ChargeController::class, 'show']);

    // charge pay
    Route::post('charges-pay', PayChargeController::class);
});
