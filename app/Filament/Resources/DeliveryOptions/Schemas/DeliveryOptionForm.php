<?php

namespace App\Filament\Resources\DeliveryOptions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

class DeliveryOptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Delivery Option Name')
                    ->required()
                    ->maxLength(255),

                Select::make('calculated_by')
                    ->label('Calculation Method')
                    ->options([
                        'flat_rate' => 'Flat rate',
                        'weight_based' => 'Weight based',
                    ])
                    ->default('flat_rate')
                    ->required()
                    ->reactive(),

                TextInput::make('rate')
                    ->label(fn($get) => 
                        $get('calculated_by') === 'flat_rate'
                            ? 'Flat Rate (£)'
                            : 'Default Rate (£)'
                    )
                    ->helperText(fn($get) =>
                        $get('calculated_by') === 'flat_rate'
                            ? 'Applies the same flat delivery rate to all orders.'
                            : 'Default charge applied if no weight range matches.'
                    )
                    ->numeric()
                    ->required()
                    ->default(0),

                Repeater::make('weightRanges')
                    ->label('Weight Ranges')
                    ->relationship('weightRanges')
                    ->visible(fn($get) => $get('calculated_by') === 'weight_based')
                    ->schema([
                        TextInput::make('min_weight')
                            ->numeric()
                            ->required()
                            ->suffix('kg'),

                        TextInput::make('max_weight')
                            ->numeric()
                            ->required()
                            ->suffix('kg'),

                        TextInput::make('rate')
                            ->numeric()
                            ->required()
                            ->label('Rate (£)'),
                    ])
                    ->addActionLabel('Add Weight Range')
                    ->columns(3)
                    ->defaultItems(1),
            ]);
    }
}
