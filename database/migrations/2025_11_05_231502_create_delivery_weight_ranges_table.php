<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_weight_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_option_id')->constrained()->onDelete('cascade');
            $table->decimal('min_weight', 8, 2);
            $table->decimal('max_weight', 8, 2);
            $table->decimal('rate', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_weight_ranges');
    }
};
