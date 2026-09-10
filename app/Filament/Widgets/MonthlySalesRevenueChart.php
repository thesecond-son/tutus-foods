<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlySalesRevenueChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Monthly Sales Revenue';

    protected function getData(): array
    {
        $sales = Order::query()
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(function ($sale) {
                return sprintf('%04d-%02d', $sale->year, $sale->month);
            });

        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $key = $date->format('Y-m');

            $labels[] = $date->format('M Y');
            $data[] = (float) ($sales[$key]->total ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sales Revenue',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}