<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Blog;

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
}
