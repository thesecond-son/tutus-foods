<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->circular(),
                    TextColumn::make('categories') // refers to the relationship
                    ->label('Categories')
                    ->getStateUsing(function ($record) {
                        // $record->categories is a Collection of Category models
                        return $record->categories->pluck('name')->join(', ');
                    })
                    ->sortable(),
                // TextColumn::make('category_id')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('base_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('weight')
                    ->label('Weight (kg)')
                    ->sortable(),
                TextColumn::make('product_options')
                    ->label('Options')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            // If structure is nested (has values inside each option)
                            return collect($state)
                                ->map(function ($option) {
                                    $name = $option['name'] ?? '';
                                    $values = collect($option['values'] ?? [])
                                        ->pluck('value')
                                        ->join(', ');
                                    return $values ? "{$name}: {$values}" : $name;
                                })
                                ->join(' | ');
                        }
                
                        return '-';
                    })
                    ->toggleable(),                
                IconColumn::make('pre_order')
                    ->boolean(),
                TextColumn::make('pre_order_expectation')
                    ->searchable(),
                IconColumn::make('seasonal')
                    ->boolean(),
                TextColumn::make('season_start')
                    ->date()
                    ->sortable(),
                TextColumn::make('season_end')
                    ->date()
                    ->sortable(),
                TextColumn::make('inventory')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
