<?php

namespace App\Filament\Resources\OrderItems\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('order_id')
                    ->numeric(),
                TextEntry::make('product.name')
                    ->label('Product')
                    ->numeric(),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('unit_price')
                    ->numeric()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('total_price')
                    ->numeric()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextEntry::make('product_options')
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
