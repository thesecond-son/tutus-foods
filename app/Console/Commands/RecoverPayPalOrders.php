<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerOrderMail;
use App\Mail\AdminOrderMail;

class RecoverPayPalOrders extends Command
{
    protected $signature = 'paypal:recover {--dry-run}';
    protected $description = 'Recover PayPal orders that were paid but not completed locally';

    public function handle()
    {
        $orders = Order::where('payment_method', 'paypal')
            ->where('payment_status', 'pending')
            ->whereNotNull('paypal_order_id')
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No PayPal orders to recover.');
            return;
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        foreach ($orders as $order) {

            $this->info("Checking PayPal order {$order->paypal_order_id}");

            $details = $provider->showOrderDetails($order->paypal_order_id);

            if (($details['status'] ?? null) !== 'COMPLETED') {
                continue;
            }

            if ($this->option('dry-run')) {
                $this->warn("DRY RUN: Would recover order {$order->id}");
                continue;
            }

            DB::transaction(function () use ($order) {

                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'completed',
                ]);

                if ($order->items()->exists()) {
                    return;
                }

                $cart = json_decode($order->cart_snapshot, true);

                $items = [];
                foreach ($cart as $item) {
                    $items[] = [
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                        'product_options' => !empty($item['options'])
                            ? json_encode($item['options'])
                            : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                OrderItem::insert($items);
            });

            Mail::to($order->customer_email)->send(new CustomerOrderMail($order));
            Mail::to('info@tutuscupcakes.com')->send(new AdminOrderMail($order));

            $this->info("Recovered order {$order->id}");
        }
    }
}
