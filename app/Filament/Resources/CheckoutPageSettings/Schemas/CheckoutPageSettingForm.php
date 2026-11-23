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
                ]),

                Section::make('Seasonal Products Notice (e.g. Shop Christmas)')
                ->description('Shown on the checkout page when the cart contains a seasonal product. Use {date} where the product\'s season start date should appear. A product\'s own "Pre-order expectation" text, if filled in, is shown instead.')
                ->schema([
                    Textarea::make('seasonal_notice')
                        ->label('Seasonal Notice Text')
                        ->placeholder(\App\Models\CheckoutPageSetting::DEFAULT_SEASONAL_NOTICE)
                        ->helperText('Leave empty to use the default: "' . \App\Models\CheckoutPageSetting::DEFAULT_SEASONAL_NOTICE . '"')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
            ]);
    }
}
