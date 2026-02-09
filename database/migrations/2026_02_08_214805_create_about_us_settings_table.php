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
        Schema::create('about_us_settings', function (Blueprint $table) {
            $table->id();

            $table->json('hero')->nullable();
            $table->json('philisophy')->nullable();
            $table->json('tutus_family')->nullable();
            $table->json('story')->nullable();
            $table->json('join_us')->nullable();
            $table->json('info')->nullable();
            $table->json('reviews')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us_settings');
    }
};
