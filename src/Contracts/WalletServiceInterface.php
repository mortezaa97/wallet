<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Contracts;

use App\Models\User;
use Mortezaa97\Wallet\Models\Charge;
use Mortezaa97\Wallet\Models\Wallet;
use Mortezaa97\Wallet\Models\Withdraw;

interface WalletServiceInterface
{
    /**
     * Create a wallet for a user.
     *
     * @param User $user
     * @param string $currency
     * @param int $createdBy
     * @return Wallet
     */
    public function createWallet(User $user, string $currency = 'IRT', int $createdBy): Wallet;
    
    public function getWalletsByUser(int $userId);
    /**
     * Get wallet by user ID.
     *
     * @param int $userId
     * @param string $currency
     * @return Wallet|null
     */
    public function getWalletByUser(int $userId, string $currency = 'IRT'): ?Wallet;

    /**
     * Get wallet by ID.
     *
     * @param int $walletId
     * @return Wallet|null
     */
    public function getWallet(int $walletId): ?Wallet;

    /**
     * Get or create wallet for a user.
     *
     * @param User $user
     * @param string $currency
     * @param int $createdBy
     * @return Wallet
     */
    public function getOrCreateWallet(User $user, string $currency = 'IRT', int $createdBy): Wallet;

    /**
     * Get wallet balance.
     *
     * @param Wallet $wallet
     * @return string
     */
    public function getBalance(Wallet $wallet): string;

    /**
     * Check if wallet has sufficient balance.
     *
     * @param Wallet $wallet
     * @param string|int $amount
     * @return bool
     */
    public function hasBalance(Wallet $wallet, string|int $amount): bool;

    /**
     * Charge wallet (add money).
     *
     * @param Wallet $wallet
     * @param string|int $amount
     * @param int $createdBy
     * @param string|null $description
     * @param \DateTime|null $expireAt
     * @return Charge
     */
    public function charge(
        Wallet $wallet,
        string|int $amount,
        int $createdBy,
        ?string $description = null,
        ?\DateTime $expireAt = null
    ): Charge;

    /**
     * Withdraw from wallet (remove money).
     *
     * @param Wallet $wallet
     * @param string|int $amount
     * @param int $bankId
     * @param int $createdBy
     * @param string|null $description
     * @param \DateTime|null $date
     * @return Withdraw
     */
    public function withdraw(
        Wallet $wallet,
        string|int $amount,
        int $bankId,
        int $createdBy,
        ?string $description = null,
        ?\DateTime $date = null
    ): Withdraw;

    /**
     * Transfer money between wallets.
     *
     * @param Wallet $fromWallet
     * @param Wallet $toWallet
     * @param string|int $amount
     * @param int $createdBy
     * @param string|null $description
     * @return array{charge: Charge, withdraw: Withdraw}
     */
    public function transfer(
        Wallet $fromWallet,
        Wallet $toWallet,
        string|int $amount,
        int $createdBy,
        ?string $description = null
    ): array;

    /**
     * Update wallet balance.
     *
     * @param Wallet $wallet
     * @param string|int $amount
     * @param int $updatedBy
     * @return Wallet
     */
    public function updateBalance(Wallet $wallet, string|int $amount, int $updatedBy): Wallet;

    /**
     * Get charges for a wallet.
     *
     * @param Wallet $wallet
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCharges(Wallet $wallet, ?int $limit = null);

    /**
     * Get charges for a user.
     *
     * @param User $user
     * @param int|null $limit
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getChargesByUser(User $user, ?int $limit = null);


    /**
     * Get withdraws for a wallet.
     *
     * @param Wallet $wallet
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWithdraws(Wallet $wallet, ?int $limit = null);

    /**
     * Get withdraws for a user.
     *
     * @param User $user
     * @param int|null $limit
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getWithdrawsByUser(User $user, ?int $limit = null);
    
    /**
     * Approve a charge.
     *
     * @param Charge $charge
     * @param int $updatedBy
     * @return Charge
     */
    public function approveCharge(Charge $charge, int $updatedBy): Charge;
    
    /**
     * Verify a charge by ID.
     *
     * @param int $id
     * @return Charge
     */
    public function verifyChargeById(int $id): Charge;

    

    /**
     * Reject a charge.
     *
     * @param Charge $charge
     * @param int $updatedBy
     * @return Charge
     */
    public function rejectCharge(Charge $charge, int $updatedBy): Charge;

    /**
     * Approve a withdraw.
     *
     * @param Withdraw $withdraw
     * @param int $updatedBy
     * @return Withdraw
     */
    public function approveWithdraw(Withdraw $withdraw, int $updatedBy): Withdraw;

    /**
     * Reject a withdraw.
     *
     * @param Withdraw $withdraw
     * @param int $updatedBy
     * @return Withdraw
     */
    public function rejectWithdraw(Withdraw $withdraw, int $updatedBy): Withdraw;
}

