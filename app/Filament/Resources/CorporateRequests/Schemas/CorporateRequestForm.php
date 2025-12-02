<?php

namespace App\Filament\Resources\CorporateRequests\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CorporateRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('purpose')
                    ->required(),
                TextInput::make('people')
                    ->required()
                    ->numeric(),
                TextInput::make('type')
                    ->required(),
                Textarea::make('requests')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
