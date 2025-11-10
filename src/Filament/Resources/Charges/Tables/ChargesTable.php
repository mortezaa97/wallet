<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Charges\Tables;

use Filament\Actions\Action;
use Mortezaa97\Wallet\Models\Charge;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ChargesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \App\Filament\Components\Table\UserTextColumn::create(),
                \Filament\Tables\Columns\TextColumn::make('wallet_id')->searchable(),
                \App\Filament\Components\Table\AmountTextColumn::create(),
                \Filament\Tables\Columns\TextColumn::make('balance_after')->suffix(' تومان ')->numeric()->sortable(),
                \App\Filament\Components\Table\DescTextColumn::create(),
                \Filament\Tables\Columns\TextColumn::make('expire_at')->dateTime()->sortable(),
                \App\Filament\Components\Table\StatusTextColumn::create(Charge::class),
                \App\Filament\Components\Table\CreatedByTextColumn::create(),
                \App\Filament\Components\Table\UpdatedByTextColumn::create(),
                \App\Filament\Components\Table\DeletedAtTextColumn::create(),
                \App\Filament\Components\Table\CreatedAtTextColumn::create(),
                \App\Filament\Components\Table\UpdatedAtTextColumn::create(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->iconButton()->tooltip('ویرایش'),
                Action::make('verify')
                    ->label('تایید شارژ')
                    ->icon('heroicon-o-bolt')
                    ->requiresConfirmation()
                    ->visible(function (Model $record) {
                        // Only show for charges that are pending (e.g., not yet verified)
                        return $record->status === \App\Enums\Status::PENDING->value;
                    })
                    ->action(function (Model $record, array $arguments) {
                        // Use WalletService to approve the charge (and update record)
                        if (method_exists(app(), 'make')) {
                            try {
                                $service = app()->make(\Mortezaa97\Wallet\Services\WalletService::class);
                                $service->approveCharge($record, auth()->id());
                            } catch (\Throwable $e) {
                                // Optionally handle approval errors, e.g. log or notify
                            }
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

