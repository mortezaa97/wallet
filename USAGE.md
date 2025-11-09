# Wallet Package Usage Guide

## Installation

The wallet service is automatically registered when the package is installed. You can use it via dependency injection or the facade.

## Basic Usage

### Using Dependency Injection

```php
use Mortezaa97\Wallet\Contracts\WalletServiceInterface;
use App\Models\User;

class YourController extends Controller
{
    public function __construct(
        protected WalletServiceInterface $walletService
    ) {}

    public function createWallet(User $user)
    {
        $wallet = $this->walletService->createWallet(
            user: $user,
            currency: 'IRT',
            createdBy: auth()->id()
        );
    }
}
```

### Using Facade

```php
use Mortezaa97\Wallet\WalletFacade;
use App\Models\User;

$wallet = WalletFacade::createWallet($user, 'IRT', auth()->id());
```

## Available Methods

### Wallet Management

#### Create Wallet
```php
$wallet = $walletService->createWallet($user, 'IRT', $userId);
```

#### Get Wallet by User
```php
$wallet = $walletService->getWalletByUser($userId, 'IRT');
```

#### Get or Create Wallet
```php
$wallet = $walletService->getOrCreateWallet($user, 'IRT', $userId);
```

#### Get Wallet Balance
```php
$balance = $walletService->getBalance($wallet);
```

#### Check Balance
```php
if ($walletService->hasBalance($wallet, 1000)) {
    // User has sufficient balance
}
```

### Charging (Adding Money)

#### Create Charge
```php
$charge = $walletService->charge(
    wallet: $wallet,
    amount: 10000,
    createdBy: auth()->id(),
    description: 'Payment for order #123',
    expireAt: new \DateTime('2024-12-31')
);
```

#### Approve Charge
```php
$charge = $walletService->approveCharge($charge, auth()->id());
// This will update the wallet balance
```

#### Reject Charge
```php
$charge = $walletService->rejectCharge($charge, auth()->id());
```

### Withdrawing (Removing Money)

#### Create Withdraw Request
```php
$withdraw = $walletService->withdraw(
    wallet: $wallet,
    amount: 5000,
    bankId: $bankId,
    createdBy: auth()->id(),
    description: 'Withdraw to bank account',
    date: new \DateTime()
);
```

#### Approve Withdraw
```php
$withdraw = $walletService->approveWithdraw($withdraw, auth()->id());
// This will deduct from wallet balance
```

#### Reject Withdraw
```php
$withdraw = $walletService->rejectWithdraw($withdraw, auth()->id());
```

### Transfer Between Wallets

```php
$result = $walletService->transfer(
    fromWallet: $user1Wallet,
    toWallet: $user2Wallet,
    amount: 1000,
    createdBy: auth()->id(),
    description: 'Payment for service'
);

// Returns: ['charge' => $charge, 'withdraw' => $withdraw]
```

### Getting Transaction History

#### Get Charges
```php
$charges = $walletService->getCharges($wallet, limit: 10);
```

#### Get Withdraws
```php
$withdraws = $walletService->getWithdraws($wallet, limit: 10);
```

### Using Model Relationships

```php
// Get wallet with relationships
$wallet = Wallet::with(['user', 'charges', 'withdraws'])->find($id);

// Get user's wallet
$user = User::find($userId);
$wallet = $user->wallet; // If you add this relationship to User model

// Get charges for wallet
$charges = $wallet->charges;

// Get withdraws for wallet
$withdraws = $wallet->withdraws;
```

## Complete Example

```php
use Mortezaa97\Wallet\Contracts\WalletServiceInterface;
use App\Models\User;

class PaymentController extends Controller
{
    public function __construct(
        protected WalletServiceInterface $walletService
    ) {}

    public function chargeWallet(Request $request, User $user)
    {
        // Get or create wallet
        $wallet = $this->walletService->getOrCreateWallet(
            user: $user,
            currency: 'IRT',
            createdBy: auth()->id()
        );

        // Create charge
        $charge = $this->walletService->charge(
            wallet: $wallet,
            amount: $request->amount,
            createdBy: auth()->id(),
            description: $request->description
        );

        // Approve charge (in real scenario, this might be done after payment verification)
        $charge = $this->walletService->approveCharge($charge, auth()->id());

        return response()->json([
            'wallet' => $wallet->fresh(),
            'charge' => $charge,
            'balance' => $this->walletService->getBalance($wallet)
        ]);
    }

    public function withdrawFromWallet(Request $request, User $user)
    {
        $wallet = $this->walletService->getWalletByUser($user->id);

        if (!$wallet) {
            return response()->json(['error' => 'Wallet not found'], 404);
        }

        // Check balance
        if (!$this->walletService->hasBalance($wallet, $request->amount)) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        // Create withdraw request
        $withdraw = $this->walletService->withdraw(
            wallet: $wallet,
            amount: $request->amount,
            bankId: $request->bank_id,
            createdBy: auth()->id(),
            description: $request->description
        );

        // In real scenario, admin would approve this later
        // $withdraw = $this->walletService->approveWithdraw($withdraw, auth()->id());

        return response()->json([
            'withdraw' => $withdraw,
            'wallet' => $wallet->fresh()
        ]);
    }

    public function transferMoney(Request $request, User $fromUser, User $toUser)
    {
        $fromWallet = $this->walletService->getWalletByUser($fromUser->id);
        $toWallet = $this->walletService->getOrCreateWallet($toUser, 'IRT', auth()->id());

        if (!$fromWallet) {
            return response()->json(['error' => 'Source wallet not found'], 404);
        }

        try {
            $result = $this->walletService->transfer(
                fromWallet: $fromWallet,
                toWallet: $toWallet,
                amount: $request->amount,
                createdBy: auth()->id(),
                description: $request->description
            );

            return response()->json([
                'success' => true,
                'transfer' => $result,
                'from_balance' => $this->walletService->getBalance($fromWallet),
                'to_balance' => $this->walletService->getBalance($toWallet)
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
```

## Status Flow

### Charge Status Flow
1. **PENDING** - Charge is created but not yet approved
2. **DONE** - Charge is approved and wallet balance is updated
3. **REJECTED** - Charge is rejected

### Withdraw Status Flow
1. **PENDING** - Withdraw request is created
2. **DONE** - Withdraw is approved and wallet balance is deducted
3. **REJECTED** - Withdraw is rejected

## Important Notes

1. **Currency Support**: Wallets support multiple currencies. Each user can have one wallet per currency.

2. **Balance Precision**: All amounts are stored as strings to avoid floating point precision issues. Use `bccomp` for comparisons.

3. **Transactions**: All operations that modify balances are wrapped in database transactions for data integrity.

4. **Approval Workflow**: Charges and withdraws are created with PENDING status. You need to explicitly approve them to update the wallet balance.

5. **Transfer**: Transfers between wallets are automatically approved and update balances immediately.

6. **Balance Checks**: Always check balance before withdrawing to avoid errors.

