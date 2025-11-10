<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Services;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mortezaa97\Wallet\Contracts\WalletServiceInterface;
use Mortezaa97\Wallet\Models\Charge;
use Mortezaa97\Wallet\Models\Wallet;
use Mortezaa97\Wallet\Models\Withdraw;
use Illuminate\Support\Str;

class WalletService implements WalletServiceInterface
{
    /**
     * Create a wallet for a user.
     */
    public function createWallet(User $user, string $currency = 'IRT', int $createdBy): Wallet
    {
        return DB::transaction(function () use ($user, $currency, $createdBy) {
            return Wallet::create([
                'user_id' => $user->id,
                'code' => (string) Str::uuid(),
                'currency' => $currency,
                'balance' => 0,
                'status' => Status::ACTIVE->value,
                'created_by' => $createdBy,
            ]);
        });
    }

    /**
     * Get all wallets by user ID.
     */
    public function getWalletsByUser(int $userId): ?Collection
    {
        return Wallet::where('user_id', $userId)
            ->get();
    }

    /**
     * Verify a charge by ID.
     */
    public function verifyChargeById(int $id): Charge
    {
        $charge = Charge::find($id);
        if (!$charge) {
            throw new \RuntimeException('Charge not found');
        }
        $charge->update([
            'status' => (string)Status::DONE->value,
        ]);
        $wallet = $this->getWallet($charge->wallet_id);
        $this->updateBalance($wallet, $charge->amount, $charge->created_by);
        return $charge->fresh();
    }

    /**
     * Get wallet by user ID.
     */
    public function getWalletByUser(int $userId, string $currency = 'IRT'): ?Wallet
    {
        return Wallet::where('user_id', $userId)
            ->where('currency', $currency)
            ->first();
    }

    /**
     * Get wallet by ID.
     */
    public function getWallet(int $walletId): ?Wallet
    {
        return Wallet::find($walletId);
    }

    /**
     * Get or create wallet for a user.
     */
    public function getOrCreateWallet(User $user, string $currency = 'IRT', int $createdBy): Wallet
    {
        $wallet = $this->getWalletByUser($user->id, $currency);

        if (!$wallet) {
            $wallet = $this->createWallet($user, $currency, $createdBy);
        }

        return $wallet;
    }

    /**
     * Get wallet balance.
     */
    public function getBalance(Wallet $wallet): string
    {
        return (string) $wallet->balance;
    }

    /**
     * Check if wallet has sufficient balance.
     */
    public function hasBalance(Wallet $wallet, string|int $amount): bool
    {
        $amount = (string) $amount;
        return bccomp((string) $wallet->balance, $amount, 0) >= 0;
    }

    /**
     * Charge wallet (add money).
     */
    public function charge(
        Wallet $wallet,
        string|int $amount,
        int $createdBy,
        ?string $description = null,
        ?\DateTime $expireAt = null
    ): Charge {
        return DB::transaction(function () use ($wallet, $amount, $createdBy, $description, $expireAt) {
            $amount = (string) $amount;
            
            // Create charge record (balance will be updated when approved)
            $charge = Charge::create([
                'user_id' => $wallet->user_id,
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'balance_after' => $wallet->balance + $amount,
                'desc' => $description,
                'expire_at' => $expireAt,
                'status' => Status::PENDING->value,
                'created_by' => $createdBy,
            ]);

            return $charge;
        });
    }

    /**
     * Withdraw from wallet (remove money).
     */
    public function withdraw(
        Wallet $wallet,
        string|int $amount,
        int $bankId,
        int $createdBy,
        ?string $description = null,
        ?\DateTime $date = null
    ): Withdraw {
        return DB::transaction(function () use ($wallet, $amount, $bankId, $createdBy, $description, $date) {
            $amount = (string) $amount;

            // Create withdraw record (balance will be updated when approved)
            $withdraw = Withdraw::create([
                'bank_id' => $bankId,
                'user_id' => $wallet->user_id,
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'balance_after' => $wallet->balance,
                'desc' => $description,
                'date' => $date?->format('Y-m-d'),
                'status' => Status::PENDING->value,
                'created_by' => $createdBy,
            ]);

            return $withdraw;
        });
    }

    /**
     * Transfer money between wallets.
     */
    public function transfer(
        Wallet $fromWallet,
        Wallet $toWallet,
        string|int $amount,
        int $createdBy,
        ?string $description = null
    ): array {
        return DB::transaction(function () use ($fromWallet, $toWallet, $amount, $createdBy, $description) {
            $amount = (string) $amount;

            if (!$this->hasBalance($fromWallet, $amount)) {
                throw new \RuntimeException('Insufficient balance in source wallet');
            }

            // Directly update balances for transfer (faster and more efficient)
            $negativeAmount = '-' . $amount;
            $this->updateBalance($fromWallet, $negativeAmount, $createdBy);
            $this->updateBalance($toWallet, $amount, $createdBy);

            // Create withdraw record from source wallet (for audit trail)
            $withdraw = Withdraw::create([
                'bank_id' => 0, // Not a real bank withdraw
                'user_id' => $fromWallet->user_id,
                'wallet_id' => $fromWallet->id,
                'amount' => $amount,
                'balance_after' => $fromWallet->fresh()->balance,
                'desc' => $description ? "Transfer to wallet #{$toWallet->id}: {$description}" : "Transfer to wallet #{$toWallet->id}",
                'status' => Status::DONE->value,
                'created_by' => $createdBy,
                'updated_by' => $createdBy,
            ]);

            // Create charge record to destination wallet (for audit trail)
            $charge = Charge::create([
                'user_id' => $toWallet->user_id,
                'wallet_id' => $toWallet->id,
                'amount' => $amount,
                'balance_after' => $toWallet->fresh()->balance,
                'desc' => $description ? "Transfer from wallet #{$fromWallet->id}: {$description}" : "Transfer from wallet #{$fromWallet->id}",
                'status' => Status::DONE->value,
                'created_by' => $createdBy,
                'updated_by' => $createdBy,
            ]);

            return [
                'charge' => $charge,
                'withdraw' => $withdraw,
            ];
        });
    }

    /**
     * Update wallet balance.
     */
    public function updateBalance(Wallet $wallet, string|int $amount, int $updatedBy): Wallet
    {
        return DB::transaction(function () use ($wallet, $amount, $updatedBy) {
            $amount = (string) $amount;
            $currentBalance = (string) $wallet->balance;
            
            // Use bcmath for precise decimal calculations
            $newBalance = bcadd($currentBalance, $amount, 0);

            // Ensure balance doesn't go negative
            if (bccomp($newBalance, '0', 0) < 0) {
                throw new \RuntimeException('Wallet balance cannot be negative');
            }

            $wallet->update([
                'balance' => $newBalance,
                'updated_by' => $updatedBy,
            ]);

            return $wallet->fresh();
        });
    }

    /**
     * Get charges for a wallet.
     */
    public function getCharges(Wallet $wallet, ?int $limit = null)
    {
        $query = Charge::where('wallet_id', $wallet->id)
            ->orderByDesc('created_at');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
    /**
     * Get charges for a user.
     */
    public function getChargesByUser(User $user, ?int $limit = null)
    {
        $query = Charge::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($limit);

        return $query;
    }

    /**
     * Get withdraws for a wallet.
     */
    public function getWithdraws(Wallet $wallet, ?int $limit = null)
    {
        $query = Withdraw::where('wallet_id', $wallet->id)
            ->orderByDesc('created_at');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
    /**
     * Get withdraws for a user.
     */
    public function getWithdrawsByUser(User $user, ?int $limit = null)
    {
        $query = Withdraw::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($limit);

        return $query;
    }
    /**
     * Approve a charge.
     */
    public function approveCharge(Charge $charge, int $updatedBy): Charge
    {
        return DB::transaction(function () use ($charge, $updatedBy) {
            if ($charge->status === Status::DONE->value) {
                return $charge;
            }

            $wallet = $charge->wallet;
            
            // Update wallet balance
            $this->updateBalance($wallet, $charge->amount, $updatedBy);

            // Update charge status
            $charge->update([
                'status' => Status::DONE->value,
                'balance_after' => $wallet->fresh()->balance,
                'updated_by' => $updatedBy,
            ]);

            return $charge->fresh();
        });
    }

    /**
     * Reject a charge.
     */
    public function rejectCharge(Charge $charge, int $updatedBy): Charge
    {
        return DB::transaction(function () use ($charge, $updatedBy) {
            if ($charge->status === Status::REJECTED->value) {
                return $charge;
            }

            // If charge was already approved and balance was updated, we need to reverse it
            if ($charge->status === Status::DONE->value) {
                $wallet = $charge->wallet;
                $negativeAmount = '-' . $charge->amount;
                $this->updateBalance($wallet, $negativeAmount, $updatedBy);
            }

            $charge->update([
                'status' => Status::REJECTED->value,
                'updated_by' => $updatedBy,
            ]);

            return $charge->fresh();
        });
    }

    /**
     * Approve a withdraw.
     */
    public function approveWithdraw(Withdraw $withdraw, int $updatedBy): Withdraw
    {
        return DB::transaction(function () use ($withdraw, $updatedBy) {
            if ($withdraw->status === Status::DONE->value) {
                return $withdraw;
            }

            $wallet = $withdraw->wallet;

            if (!$this->hasBalance($wallet, $withdraw->amount)) {
                throw new \RuntimeException('Insufficient wallet balance');
            }

            // Update wallet balance (subtract)
            $negativeAmount = '-' . $withdraw->amount;
            $this->updateBalance($wallet, $negativeAmount, $updatedBy);

            // Update withdraw status
            $withdraw->update([
                'status' => Status::DONE->value,
                'balance_after' => $wallet->fresh()->balance,
                'updated_by' => $updatedBy,
            ]);

            return $withdraw->fresh();
        });
    }

    /**
     * Reject a withdraw.
     */
    public function rejectWithdraw(Withdraw $withdraw, int $updatedBy): Withdraw
    {
        return DB::transaction(function () use ($withdraw, $updatedBy) {
            if ($withdraw->status === Status::REJECTED->value) {
                return $withdraw;
            }

            // If withdraw was already approved and balance was updated, we need to reverse it
            if ($withdraw->status === Status::DONE->value) {
                $wallet = $withdraw->wallet;
                $this->updateBalance($wallet, $withdraw->amount, $updatedBy);
            }

            $withdraw->update([
                'status' => Status::REJECTED->value,
                'updated_by' => $updatedBy,
            ]);

            return $withdraw->fresh();
        });
    }
}

