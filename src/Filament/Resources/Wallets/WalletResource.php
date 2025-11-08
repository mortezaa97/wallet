<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Wallets;

use Mortezaa97\Wallet\Filament\Resources\Wallets\Pages\CreateWallet;
use Mortezaa97\Wallet\Filament\Resources\Wallets\Pages\EditWallet;
use Mortezaa97\Wallet\Filament\Resources\Wallets\Pages\ListWallets;
use Mortezaa97\Wallet\Filament\Resources\Wallets\Schemas\WalletForm;
use Mortezaa97\Wallet\Filament\Resources\Wallets\Tables\WalletsTable;
use Mortezaa97\Wallet\Models\Wallet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Wallet';

    public static function form(Schema $schema): Schema
    {
        return WalletForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WalletsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWallets::route('/'),
            'create' => CreateWallet::route('/create'),
            'edit' => EditWallet::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

