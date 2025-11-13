<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CategoriesOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Categories', Category::count())
            ->description('Product categories available')
            ->color('info'),
        ];
    }
}
