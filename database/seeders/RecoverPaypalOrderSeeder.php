<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;

class RecoverPaypalOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'order_number' => '3PB28507GW981280D',
            'customer_email' => 'yettykay1011@yahoo.com',
            'customer_first_name' => 'Yetunde',
            'customer_last_name' => 'Kolawole',
            'customer_phone' => null,

            'delivery_type' => 'delivery',
            'delivery_address' => '61 Albertine Road',
            'delivery_town' => 'Langley',
            'delivery_postcode' => 'ME17 3UP',
            'delivery_option_id' => 1,
            'delivery_fee' => 5.75,

            'pickup_location_id' => null,
            'pickup_date' => null,
            'pickup_time' => null,

            'total_amount' => 17.75,
            'payment_method' => 'paypal',
            'payment_status' => 'completed',
            'delivery_pickup_status' => 'pending',

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
