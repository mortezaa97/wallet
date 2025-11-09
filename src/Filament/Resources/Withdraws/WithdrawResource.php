<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Withdraws;

use Mortezaa97\Wallet\Filament\Resources\Withdraws\Pages\CreateWithdraw;
use Mortezaa97\Wallet\Filament\Resources\Withdraws\Pages\EditWithdraw;
use Mortezaa97\Wallet\Filament\Resources\Withdraws\Pages\ListWithdraws;
use Mortezaa97\Wallet\Filament\Resources\Withdraws\Schemas\WithdrawForm;
use Mortezaa97\Wallet\Filament\Resources\Withdraws\Tables\WithdrawsTable;
use Mortezaa97\Wallet\Models\Withdraw;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class WithdrawResource extends Resource
{
    protected static ?string $model = Withdraw::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'برداشت ها';

    protected static ?string $modelLabel = 'برداشت';

    protected static ?string $pluralModelLabel = 'برداشت ها';

    protected static string|null|UnitEnum $navigationGroup = 'مالی';

    public static function form(Schema $schema): Schema
    {
        return WithdrawForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WithdrawsTable::configure($table);
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
            'index' => ListWithdraws::route('/'),
            'create' => CreateWithdraw::route('/create'),
            'edit' => EditWithdraw::route('/{record}/edit'),
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

