<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use Filament\Facades\Filament;

class OrdersWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected static ?string $title = 'Dashboard Overview';

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {

        return [
            Stat::make('Total Orders', Order::count())
                ->description('All orders currently listed')
                ->color('success'),   
        ];

    }

    public static function canView(): bool
    {
        $user = Filament::auth()->user();
        return $user && $user->hasRole('admin'); // only admins can see
    }
}
