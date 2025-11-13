<?php

namespace App\Filament\Resources\PickupLocations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PickupLocationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('address')
                    ->columnSpanFull(),
                TextEntry::make('available_days')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('start_time')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('end_time')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('interval_minutes')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
