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
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(function ($order) {
                return sprintf('%04d-%02d', $order->year, $order->month);
            });

        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $key = $date->format('Y-m');

            $labels[] = $date->format('M Y');
            $data[] = (int) ($orders[$key]->total ?? 0);
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