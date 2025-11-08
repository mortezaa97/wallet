<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Withdraws\Schemas;

use Filament\Schemas\Schema;

class WithdrawForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Group::make()
                ->schema([
                    \Filament\Schemas\Components\Section::make()
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('bank_id')->required(),
                            \Filament\Forms\Components\TextInput::make('user_id')->required(),
                            \App\Filament\Components\Form\StatusSelect::create()->required(),
                            \Filament\Forms\Components\TextInput::make('wallet_id')->required(),
                            \App\Filament\Components\Form\AmountTextInput::create()->required(),
                            \Filament\Forms\Components\TextInput::make('balance_after')->required(),
                            \Filament\Forms\Components\DatePicker::make('date'),
                            \App\Filament\Components\Form\DescTextarea::create(),
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

