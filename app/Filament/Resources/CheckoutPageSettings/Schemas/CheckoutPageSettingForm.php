<?php

namespace App\Filament\Resources\CheckoutPageSettings\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CheckoutPageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Delivery / Posting Notice')
                ->description('This message appears on the checkout page beneath the delivery method selector.')
                ->schema([
                    Textarea::make('shipping_notice')
                        ->label('Shipping Notice Text')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                ])
            ]);
    }
}
