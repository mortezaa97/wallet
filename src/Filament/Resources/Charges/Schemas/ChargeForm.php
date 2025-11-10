<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Charges\Schemas;

use Filament\Schemas\Schema;
use Mortezaa97\Wallet\Models\Charge;

class ChargeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Group::make()
                ->schema([
                    \Filament\Schemas\Components\Section::make()
                        ->schema([
                            \Filament\Forms\Components\Select::make('user_id')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->columnSpan(4)
                                ->required(),

                            \Filament\Forms\Components\Select::make('wallet_id')
                                ->relationship('wallet', 'code')
                                ->searchable()
                                ->columnSpan(4)
                                ->required(),

                            \App\Filament\Components\Form\AmountTextInput::create()
                                ->suffix(' تومان ')
                                ->columnSpan(4)
                                ->required(),

                            \Filament\Forms\Components\TextInput::make('balance_after')
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(4)
                                ->afterStateHydrated(function ($component, $state, $record) {
                                    // If editing, or have a way to get prior balance and current amount
                                    // Implement your logic here if needed or leave as-is for calculation elsewhere
                                }),
                            \App\Filament\Components\Form\DescTextarea::create()
                                ->columnSpan(4)
                                ->required(),
                            \Filament\Forms\Components\DateTimePicker::make('expire_at')
                                ->columnSpan(4)
                                ->required(),
                            \App\Filament\Components\Form\StatusSelect::create(Charge::class)->required(),
                            \App\Filament\Components\Form\CreatedBySelect::create()->required(),
                            \App\Filament\Components\Form\UpdatedBySelect::create(),

                        ])
                        ->columns(12)
                        ->columnSpan(12),
                ])
                ->columns(12)
                ->columnSpan(8),
            \Filament\Schemas\Components\Group::make()
                ->schema([
                    \Filament\Schemas\Components\Section::make()
                        ->schema([])
                        ->columns(12)
                        ->columnSpan(12),
                ])
                ->columns(12)
                ->columnSpan(4),
        ])
            ->columns(12);
    }
}

