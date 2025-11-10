<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Wallets\Tables;

use Mortezaa97\Wallet\Models\Wallet;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class WalletsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \App\Filament\Components\Table\CodeTextColumn::create(),
                \Filament\Tables\Columns\TextColumn::make('user_id')
                    ->translateLabel()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                \App\Filament\Components\Table\UserTextColumn::create(),
                \Filament\Tables\Columns\TextColumn::make('balance')->suffix(' تومان ')->numeric()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('currency')->searchable(),
                \App\Filament\Components\Table\StatusTextColumn::create(Wallet::class),
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

