<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersPerMonthChart extends ChartWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Orders Per Month';

    protected function getData(): array
{
    $orders = Order::query()
        ->where('payment_status', 'paid')
        ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
        ->get(['created_at']);

    $monthlyOrders = [];

    foreach ($orders as $order) {
        $month = $order->created_at->format('Y-m');

        $monthlyOrders[$month] = ($monthlyOrders[$month] ?? 0) + 1;
    }

    $labels = [];
    $data = [];

    for ($i = 11; $i >= 0; $i--) {
        $date = now()->subMonths($i);

        $key = $date->format('Y-m');

        $labels[] = $date->format('M Y');
        $data[] = $monthlyOrders[$key] ?? 0;
    }

    return [
        'datasets' => [
            [
                'label' => 'Orders',
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