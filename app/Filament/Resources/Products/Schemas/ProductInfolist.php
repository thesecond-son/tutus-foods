<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('sku')
                    ->label('SKU')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('allergens')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('image_url')
                    ->label('Image')
                    ->getStateUsing(fn ($record) => $record->image_url)
                    ->placeholder('-')
                    ->extraAttributes([
                        'style' => 'width:150px; height:150px; object-fit:cover; border-radius:8px;',
                    ]),
                TextEntry::make('image_alt')
                    ->label('Image Alt Text')
                    ->placeholder('-'),
                TextEntry::make('category.name')
                    ->label('Category')
                    ->placeholder('-'),
                TextEntry::make('product_options')
                    ->label('Options')
                    ->getStateUsing(function ($record) {
                        $options = $record->product_options;
                        if (!is_array($options)) {
                            return '-';
                        }
                
                        return collect($options)
                            ->map(function ($option) {
                                $name = $option['name'] ?? '';
                                $values = collect($option['values'] ?? [])
                                    ->pluck('value')
                                    ->join(', ');
                                return $values ? "{$name}: {$values}" : $name;
                            })
                            ->join(' | ');
                    })
                    ->columnSpanFull(),                
                TextEntry::make('base_price')
                    ->numeric(),
                IconEntry::make('pre_order')
                    ->boolean(),
                TextEntry::make('pre_order_expectation')
                    ->placeholder('-'),
                IconEntry::make('seasonal')
                    ->boolean(),
                TextEntry::make('season_start')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('season_end')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('inventory')
                    ->numeric(),
                IconEntry::make('active')
                    ->boolean(),
                IconEntry::make('pickup_only')
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
