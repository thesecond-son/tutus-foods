<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

/**
 * Manual reconciliation seeder.
 *
 * Records the order for the customer whose Square payment succeeded on
 * 2026-07-30 but was never saved due to the empty-cart/session bug
 * (payment ID: npxFX5Sf6fWmu5Xr0bTTmIjrh9MZY).
 *
 * ⚠️ TODO before running: the actual cart items for this order were never
 * logged (the crash happened before the "Items Ordered" log line), so the
 * $items array below is a placeholder. Confirm the real product(s),
 * quantities, and prices with the client or from the Square receipt, then
 * update $items so unit_price * quantity sums correctly with the known
 * order total below.
 *
 * Run with:
 *   php artisan db:seed --class=Database\\Seeders\\ManualSquareOrder30JulSeeder
 */
class ManualSquareOrder30JulSeeder extends Seeder
{
    public function run(): void
    {
        // Known facts from the Square payment log (2026-07-30 14:45:59–14:46:01)
        $paymentReference = 'npxFX5Sf6fWmu5Xr0bTTmIjrh9MZY'; // Square payment ID
        $deliveryFee       = 5.50;
        $totalAmount       = 5.50; // amount_money captured by Square (550 pence)

        // ⚠️ Guard against creating a duplicate if this is run twice
        if (Order::where('payment_reference', $paymentReference)->exists()) {
            $this->command?->warn("Order for payment {$paymentReference} already exists — skipping.");
            return;
        }

        DB::transaction(function () use ($paymentReference, $deliveryFee, $totalAmount) {
            $order = Order::create([
                'order_number'           => 'ORD-RECON-' . strtoupper(substr(md5($paymentReference), 0, 8)),
                'customer_email'         => 'ifeomaodetunde@gmail.com',
                'customer_first_name'    => 'ifeoma',
                'customer_last_name'     => 'Odetunde',
                'customer_phone'         => '07540392944',
                'customer_instagram'     => null,
                'order_notes'            => 'Manually reconciled — payment succeeded on 2026-07-30 but order was not recorded due to a checkout bug (session cart empty during 3DS verification). See payment_reference for the Square payment ID.',
                'delivery_type'          => 'delivery',
                'delivery_option_id'     => 1,
                'delivery_address'       => '17 Hopes Farm View',
                'delivery_town'          => 'Leeds',
                'delivery_postcode'      => 'LS10 4QR',
                'delivery_fee'           => $deliveryFee,
                'pickup_location_id'     => null,
                'pickup_date'            => null,
                'pickup_time'            => null,
                'total_amount'           => $totalAmount,
                'payment_method'         => 'square',
                'payment_status'         => 'paid',
                'status'                 => 'completed',
                'payment_reference'      => $paymentReference,
                'delivery_pickup_status' => false,
            ]);

            // ⚠️ PLACEHOLDER — replace with the actual item(s) ordered.
            // unit_price/total_price here are guesses to make the numbers add up
            // to the known charged total; confirm with the client and correct.
            // $items = [
            //     [
            //         'order_id'        => $order->id,
            //         'product_id'      => null, // TODO: set the real product_id
            //         'quantity'        => 1,
            //         'unit_price'      => 0.00,  // TODO: confirm actual item price
            //         'total_price'     => 0.00,  // TODO: quantity * unit_price
            //         'product_options' => null,  // TODO: e.g. json_encode(['Flavour' => '...'])
            //         'created_at'      => now(),
            //         'updated_at'      => now(),
            //     ],
            // ];

            // OrderItem::insert($items);

            $this->command?->info("Reconciled order #{$order->id} ({$order->order_number}) for payment {$paymentReference}.");
        });
    }
}