<?php

namespace App\Filament\Widgets;


use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Facades\Filament;

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

    public static function canView(): bool
    {
        $user = Filament::auth()->user();
        return $user && $user->hasRole('admin'); // only admins can see
    }
}
