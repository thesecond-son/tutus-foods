<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Facades\Filament;

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

    public static function canView(): bool
    {
        $user = Filament::auth()->user();
        return $user && $user->hasRole('admin'); // only admins can see
    }
}
