<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Mortezaa97\Wallet\Models\Wallet createWallet(\App\Models\User $user, string $currency = 'IRT', int $createdBy)
 * @method static \Mortezaa97\Wallet\Models\Wallet|null getWalletByUser(int $userId, string $currency = 'IRT')
 * @method static \Mortezaa97\Wallet\Models\Wallet|null getWallet(int $walletId)
 * @method static \Mortezaa97\Wallet\Models\Wallet getOrCreateWallet(\App\Models\User $user, string $currency = 'IRT', int $createdBy)
 * @method static string getBalance(\Mortezaa97\Wallet\Models\Wallet $wallet)
 * @method static bool hasBalance(\Mortezaa97\Wallet\Models\Wallet $wallet, string|int $amount)
 * @method static \Mortezaa97\Wallet\Models\Charge charge(\Mortezaa97\Wallet\Models\Wallet $wallet, string|int $amount, int $createdBy, ?string $description = null, ?\DateTime $expireAt = null)
 * @method static \Mortezaa97\Wallet\Models\Withdraw withdraw(\Mortezaa97\Wallet\Models\Wallet $wallet, string|int $amount, int $bankId, int $createdBy, ?string $description = null, ?\DateTime $date = null)
 * @method static array transfer(\Mortezaa97\Wallet\Models\Wallet $fromWallet, \Mortezaa97\Wallet\Models\Wallet $toWallet, string|int $amount, int $createdBy, ?string $description = null)
 * @method static \Mortezaa97\Wallet\Models\Wallet updateBalance(\Mortezaa97\Wallet\Models\Wallet $wallet, string|int $amount, int $updatedBy)
 * @method static \Illuminate\Database\Eloquent\Collection getCharges(\Mortezaa97\Wallet\Models\Wallet $wallet, ?int $limit = null)
 * @method static \Illuminate\Database\Eloquent\Collection getWithdraws(\Mortezaa97\Wallet\Models\Wallet $wallet, ?int $limit = null)
 * @method static \Mortezaa97\Wallet\Models\Charge approveCharge(\Mortezaa97\Wallet\Models\Charge $charge, int $updatedBy)
 * @method static \Mortezaa97\Wallet\Models\Charge rejectCharge(\Mortezaa97\Wallet\Models\Charge $charge, int $updatedBy)
 * @method static \Mortezaa97\Wallet\Models\Withdraw approveWithdraw(\Mortezaa97\Wallet\Models\Withdraw $withdraw, int $updatedBy)
 * @method static \Mortezaa97\Wallet\Models\Withdraw rejectWithdraw(\Mortezaa97\Wallet\Models\Withdraw $withdraw, int $updatedBy)
 *
 * @see \Mortezaa97\Wallet\Contracts\WalletServiceInterface
 * @see \Mortezaa97\Wallet\Services\WalletService
 */
class WalletFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Mortezaa97\Wallet\Contracts\WalletServiceInterface::class;
    }
}
