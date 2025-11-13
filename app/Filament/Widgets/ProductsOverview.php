<?php

namespace App\Filament\Widgets;


use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
            ->description('All products currently listed')
            ->color('success'),
        ];
    }
}
