<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Charges;

use Mortezaa97\Wallet\Filament\Resources\Charges\Pages\CreateCharge;
use Mortezaa97\Wallet\Filament\Resources\Charges\Pages\EditCharge;
use Mortezaa97\Wallet\Filament\Resources\Charges\Pages\ListCharges;
use Mortezaa97\Wallet\Filament\Resources\Charges\Schemas\ChargeForm;
use Mortezaa97\Wallet\Filament\Resources\Charges\Tables\ChargesTable;
use Mortezaa97\Wallet\Models\Charge;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChargeResource extends Resource
{
    protected static ?string $model = Charge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Charge';

    public static function form(Schema $schema): Schema
    {
        return ChargeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChargesTable::configure($table);
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
            'index' => ListCharges::route('/'),
            'create' => CreateCharge::route('/create'),
            'edit' => EditCharge::route('/{record}/edit'),
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

