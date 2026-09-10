<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Order;

class LatestOrders extends TableWidget
{
    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Order::latest())
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_first_name')
                    ->label('Customer')
                    ->formatStateUsing(fn ($state, $record) => 
                        $record->customer_first_name . ' ' . $record->customer_last_name
                    )
                    ->sortable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),

                Tables\Columns\TextColumn::make('total_amount')
                    ->money('GBP')
                    ->label('Total')
                    ->sortable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger' => 'failed',
                        'warning' => 'pending',
                        'success' => 'paid',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
