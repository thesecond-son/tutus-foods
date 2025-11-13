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
        Schema::create('delivery_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('calculated_by', ['flat_rate', 'weight_based'])->default('flat_rate');
            $table->decimal('rate', 10, 2)->nullable(); // only used for flat rate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_options');
    }
};
