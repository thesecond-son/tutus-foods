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
                TextEntry::make('customer_email')
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('customer_first_name')
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('customer_last_name')
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('customer_phone')
                    ->placeholder('-')
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('customer_instagram')
                    ->placeholder('-')
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('order_notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('delivery_type')
                    ->badge(),
                TextEntry::make('delivery_option_id')
                    ->label('Delivery Option')
                    ->getStateUsing(fn($record) => $record->deliveryOption?->name ?? '-'),
                TextEntry::make('delivery_address')
                    ->placeholder('Delivery Address')
                    ->columnSpanFull(),
                TextEntry::make('delivery_town')
                    ->placeholder('Delivery Town')
                    ->columnSpanFull(),
                TextEntry::make('delivery_postcode')
                    ->placeholder('Delivery Postcode')
                    ->columnSpanFull(),
                TextEntry::make('pickup_location_id')
                    ->label('Pickup Location')
                    ->getStateUsing(fn($record) => $record->pickupLocation?->name ?? '-')
                    ->placeholder('-'),
                TextEntry::make('pickup_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('pickup_time')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('total_amount')
                    ->numeric()
                    ->prefix('€')
                    ->placeholder('-')
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('payment_method')
                    ->badge()
                    ->placeholder('-')
                    ->visible(fn () => auth()->user()->hasRole('admin')),
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
