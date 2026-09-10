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
    $orders = Order::query()
        ->where('payment_status', 'paid')
        ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
        ->get(['created_at', 'total_amount']);

    $monthlySales = [];

    foreach ($orders as $order) {
        $month = $order->created_at->format('Y-m');

        $monthlySales[$month] = ($monthlySales[$month] ?? 0)
            + (float) $order->total_amount;
    }

    $labels = [];
    $data = [];

    for ($i = 11; $i >= 0; $i--) {
        $date = now()->subMonths($i);

        $key = $date->format('Y-m');

        $labels[] = $date->format('M Y');
        $data[] = $monthlySales[$key] ?? 0;
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