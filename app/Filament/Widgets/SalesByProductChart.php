<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class SalesByProductChart extends ChartWidget
{
    protected static ?int $sort = 6;

    protected ?string $heading = 'Sales by Product';

    protected function getData(): array
    {
        $productSales = [];

        $orders = Order::query()
            ->where('payment_status', 'paid')
            ->get(['cart_snapshot']);

        foreach ($orders as $order) {
            $cart = $order->cart_snapshot;

            if (is_string($cart)) {
                $cart = json_decode($cart, true);
            }

            if (! is_array($cart)) {
                continue;
            }

            foreach ($cart as $item) {
                $name = $item['name'] ?? 'Unknown Product';
                $price = (float) ($item['price'] ?? 0);
                $quantity = (float) ($item['quantity'] ?? 0);

                $productSales[$name] = ($productSales[$name] ?? 0)
                    + ($price * $quantity);
            }
        }

        arsort($productSales);

        return [
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => array_values($productSales),
                ],
            ],
            'labels' => array_keys($productSales),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}