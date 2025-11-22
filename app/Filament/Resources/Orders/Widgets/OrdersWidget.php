<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            
                Stat::make('Total Orders', Order::count())
                ->description('All products currently listed')
                ->color('success'),   
        ];
    }
}
