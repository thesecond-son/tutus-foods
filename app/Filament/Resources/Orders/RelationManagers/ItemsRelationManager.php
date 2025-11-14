<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Filament\Resources\OrderItems\OrderItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $relatedResource = OrderItemResource::class;

    public function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('product.name')->label('Product'),
            TextColumn::make('quantity'),
            TextColumn::make('unit_price')->money('GBP'),
            TextColumn::make('total_price')->money('GBP'),
        ])
        ->filters([
            //
        ])
        ->headerActions([
            CreateAction::make(),
        ]);
    }
}
