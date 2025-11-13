<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_email');
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->string('customer_phone');
            $table->string('customer_instagram')->nullable();
            $table->text('order_notes')->nullable();
            $table->enum('delivery_type', ['pickup','delivery']);
            $table->foreignId('delivery_option_id')->nullable()->constrained('delivery_options')->nullOnDelete();
            $table->text('delivery_address')->nullable();
            $table->foreignId('pickup_location_id')->nullable()->constrained('pickup_locations')->nullOnDelete();
            $table->date('pickup_date')->nullable();
            $table->time('pickup_time')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['square','paypal']);
            $table->enum('payment_status', ['pending','paid','failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
