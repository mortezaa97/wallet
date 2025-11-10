<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Tables\Actions;

use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Mortezaa97\Wallet\Services\WalletService;

class ChargeUserWalletAction
{
    public static function make(string $name = 'charge_wallet'): Action
    {
        return Action::make($name)
            ->label('شارژ کیف پول')
            ->iconButton()
            ->tooltip('شارژ کیف پول')
            ->color('success')
            ->icon('heroicon-o-banknotes')
            ->modalHeading('افزودن شارژ کیف پول')
            ->modalSubmitActionLabel('ثبت شارژ')
            ->modalWidth('md')
            ->authorize(fn (): bool => Auth::check())
            ->schema(fn (Model $record): array => self::getFormSchema($record))
            ->action(function (Model $record, array $data): void {
                self::handleAction($record, $data);
            });
    }

    protected static function getFormSchema(Model $record): array
    {
        if (! $record instanceof User) {
            throw new \InvalidArgumentException('ChargeUserWalletAction can only be used with instances of App\Models\User.');
        }

        /** @var WalletService $walletService */
        $walletService = app(WalletService::class);

        $wallets = $walletService->getWalletsByUser($record->getKey())?->pluck('code', 'id')->toArray() ?? [];

        return [
            Select::make('wallet_id')
                ->label('کیف پول')
                ->options($wallets)
                ->searchable()
                ->native(false)
                ->placeholder('کیف پول را انتخاب کنید')
                ->hidden(empty($wallets))
                ->columnSpanFull(),
            TextInput::make('amount')
                ->label('مبلغ شارژ')
                ->required()
                ->suffix(' تومان ')
                ->columnSpanFull(),
            Textarea::make('desc')
                ->label('توضیحات')
                ->columnSpanFull(),
            DateTimePicker::make('expire_at')
                ->label('تاریخ انقضا')
                ->seconds(false)
                ->jalali()
                ->nullable()
                ->columnSpanFull(),
            Toggle::make('verify_now')
                ->label('تایید خودکار شارژ')
                ->default(true)
                ->columnSpanFull(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected static function handleAction(Model $record, array $data): void
    {
        if (! $record instanceof User) {
            Notification::make()
                ->title('امکان ثبت شارژ وجود ندارد.')
                ->danger()
                ->send();

            return;
        }

        $adminId = Auth::id();

        if (! $adminId) {
            Notification::make()
                ->title('کاربر احراز هویت نشده است')
                ->danger()
                ->send();

            return;
        }

        /** @var WalletService $walletService */
        $walletService = app(WalletService::class);

        try {
            $wallet = ! empty($data['wallet_id'])
                ? $walletService->getWallet((int) $data['wallet_id'])
                : null;

            if (! $wallet) {
                $currency = config('wallet.default_currency', 'IRT');
                $wallet = $walletService->getOrCreateWallet($record, (string) $currency, $adminId);
            }

            $charge = $walletService->charge(
                $wallet,
                $data['amount'],
                $adminId,
                $data['desc'] ?? null,
                ! empty($data['expire_at']) ? Carbon::parse($data['expire_at']) : null,
            );

            if ($data['verify_now'] ?? true) {
                $walletService->approveCharge($charge, $adminId);
            }

            Notification::make()
                ->title('شارژ با موفقیت ثبت شد')
                ->color('success')
                ->success()
                ->send();
        } catch (\Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('خطا در ثبت شارژ')
                ->body($exception->getMessage())
                ->color('danger')
                ->danger()
                ->send();
        }
    }
}


