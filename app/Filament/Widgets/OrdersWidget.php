<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;

class OrdersWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static ?string $title = 'Dashboard Overview';

    protected function getStats(): array
    {

        return [
            Stat::make('Total Orders', Order::count())
                ->description('All orders currently listed')
                ->color('success'),   
        ];

    }
}
