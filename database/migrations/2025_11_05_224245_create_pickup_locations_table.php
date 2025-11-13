<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pickup_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Hockley Kitchen Pickup"
            $table->text('address'); // e.g. "Tutu's Cupcake Studio, Unit 5 Foundry Business Pk, Hockley SS5 4HS, UK"
            $table->json('available_days')->nullable(); // ["Tuesday", "Friday"]
            $table->time('start_time')->nullable(); // 12:00
            $table->time('end_time')->nullable();   // 17:00
            $table->integer('interval_minutes')->default(30); // e.g. 30 minutes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_locations');
    }
};
