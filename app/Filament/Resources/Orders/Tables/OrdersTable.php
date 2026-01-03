<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable(),
                TextColumn::make('customer_email')
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextColumn::make('customer_first_name')
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextColumn::make('customer_last_name')
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextColumn::make('customer_phone')
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextColumn::make('customer_instagram')
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextColumn::make('delivery_type')
                    ->badge(),
                TextColumn::make('deliveryOption.name')
                    ->label('Delivery Option')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('delivery_address')
                    ->label('Delivery Address'),
                TextColumn::make('delivery_town')
                    ->label('Delivery Town'),
                TextColumn::make('delivery_postcode')
                    ->label('Delivery Postcode'),
                TextColumn::make('pickupLocation.name')
                    ->label('Pickup Location')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('pickup_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('pickup_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextColumn::make('payment_method')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('admin')), 
                TextColumn::make('payment_status')
                    ->badge(),
                IconColumn::make('delivery_pickup_status')
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
                EditAction::make()
                ->visible(fn () => auth()->user()->hasRole('admin')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                ]),
            ]);
    }
}
