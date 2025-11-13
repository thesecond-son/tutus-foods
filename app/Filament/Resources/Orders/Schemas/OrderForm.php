<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required(),
                TextInput::make('customer_email')
                    ->email()
                    ->required(),
                TextInput::make('customer_first_name')
                    ->required(),
                TextInput::make('customer_last_name')
                    ->required(),
                TextInput::make('customer_phone')
                    ->tel()
                    ->required(),
                TextInput::make('customer_instagram')
                    ->default(null),
                Textarea::make('order_notes')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('delivery_type')
                    ->options(['pickup' => 'Pickup', 'delivery' => 'Delivery'])
                    ->required(),
                TextInput::make('delivery_option_id')
                    ->numeric()
                    ->default(null),
                Textarea::make('delivery_address')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('pickup_location_id')
                    ->numeric()
                    ->default(null),
                DatePicker::make('pickup_date'),
                TimePicker::make('pickup_time'),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                Select::make('payment_method')
                    ->options(['square' => 'Square', 'paypal' => 'Paypal'])
                    ->required(),
                Select::make('payment_status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed'])
                    ->default('pending')
                    ->required(),
                Toggle::make('delivery_pickup_status')
                    ->required(),
            ]);
    }
}
