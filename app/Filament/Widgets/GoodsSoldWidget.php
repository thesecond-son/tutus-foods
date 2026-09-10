<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GoodsSoldWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        $totalGoodsSold = Order::where('payment_status', 'paid')
            ->sum('total_amount');

        return [
            Stat::make(
                'Total Goods Sold',
                '£' . number_format($totalGoodsSold, 2)
            )
                ->description('Total value of goods sold')
                ->color('success'),
        ];
    }
}