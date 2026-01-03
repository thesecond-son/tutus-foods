<?php

namespace App\Filament\Resources\CorporateRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CorporateRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('phone')
                ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-')
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('purpose'),
                TextEntry::make('people')
                    ->numeric(),
                TextEntry::make('type'),
                TextEntry::make('requests')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
