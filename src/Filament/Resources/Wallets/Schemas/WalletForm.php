<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Filament\Resources\Wallets\Schemas;

use Filament\Schemas\Schema;

class WalletForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Group::make()
                ->schema([
                    \Filament\Schemas\Components\Section::make()
                        ->schema([
                            \App\Filament\Components\Form\CodeTextInput::create()->required(),
                            \Filament\Forms\Components\TextInput::make('user_id')->required(),
                            \Filament\Forms\Components\TextInput::make('balance')->required(),
                            \Filament\Forms\Components\TextInput::make('currency')->required()->maxLength(8),
                            \App\Filament\Components\Form\StatusSelect::create()->required(),
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

