<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sku')
                     ->label('SKU')
                    ->default(null),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('allergens')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->visibility('public')
                    ->preserveFilenames(),
                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->default(null),
                Repeater::make('product_options')
                    ->label('Product Variants')
                    ->schema([
                        TextInput::make('name')
                            ->label('Option Name')
                            ->placeholder('e.g., Flavor, Size')
                            ->required(),
                
                        Repeater::make('values')
                            ->label('Values')
                            ->schema([
                                TextInput::make('value')
                                    ->label('Variant Value')
                                    ->placeholder('e.g., Chocolate, Vanilla, 500g')
                                    ->required(),
                            ])
                            ->defaultItems(1)
                            ->collapsed()
                            ->columns(1),
                    ])
                    ->defaultItems(1)
                    ->columns(1)
                    ->collapsed()
                    ->columnSpanFull()
                    ->addActionLabel('Add Option'),             
                TextInput::make('base_price')
                    ->numeric()
                    ->required(),
                TextInput::make('weight')
                    ->label('Weight (kg)')
                    ->numeric()
                    ->step(0.01)
                    ->placeholder('e.g., 1.5')
                    ->required(),
                Toggle::make('pre_order')
                    ->required(),
                TextInput::make('pre_order_expectation')
                    ->default(null),
                Toggle::make('seasonal')
                    ->required(),
                DatePicker::make('season_start'),
                DatePicker::make('season_end'),
                TextInput::make('inventory')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('active')
                    ->required(),
                Toggle::make('pickup_only')
                    ->required(),
            ]);
    }
}
