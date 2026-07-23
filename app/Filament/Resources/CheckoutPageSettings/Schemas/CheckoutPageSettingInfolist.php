<?php

namespace App\Filament\Resources\CheckoutPageSettings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CheckoutPageSettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Delivery / Posting Notice')
                ->schema([
                    TextEntry::make('shipping_notice')
                        ->label('Shipping Notice Text')
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
 
                Section::make('Meta')
                    ->schema([
                        TextEntry::make('created_at')->dateTime(),
                        TextEntry::make('updated_at')->dateTime(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
