<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order_number'),
                TextEntry::make('customer_email'),
                TextEntry::make('customer_first_name'),
                TextEntry::make('customer_last_name'),
                TextEntry::make('customer_phone'),
                TextEntry::make('customer_instagram')
                    ->placeholder('-'),
                TextEntry::make('order_notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('delivery_type')
                    ->badge(),
                TextEntry::make('delivery_option_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('delivery_address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('pickup_location_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('pickup_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('pickup_time')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('total_amount')
                    ->numeric(),
                TextEntry::make('payment_method')
                    ->badge(),
                TextEntry::make('payment_status')
                    ->badge(),
                IconEntry::make('delivery_pickup_status')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
