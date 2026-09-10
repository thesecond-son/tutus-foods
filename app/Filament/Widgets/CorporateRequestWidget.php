<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\CorporateRequest;

class CorporateRequestWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 10;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Corporate Requests', CorporateRequest::count())
                ->description('All corporate requests currently listed')
                ->color('success'),  
        ];
    }
}