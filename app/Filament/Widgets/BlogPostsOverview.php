<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Blog;
use Filament\Facades\Filament;

class BlogPostsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Blog Posts', Blog::count())
            ->description('All blog posts currently listed')
            ->color('success'), 
        ];
    }

   public static function canView(): bool
    {
        $user = Filament::auth()->user();
        return $user && $user->hasRole('admin'); // only admins can see
    }
}
