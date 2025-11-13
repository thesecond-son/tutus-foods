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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('allergens')->nullable();
            $table->string('image_path')->nullable();
        
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
        
            // JSON field for product options (flavors, colors, etc)
            $table->json('product_options')->nullable();
            /*
                Example structure:
                {
                    "flavor": ["Chocolate", "Vanilla", "Red Velvet"],
                    "color": ["Pink", "Yellow"]
                }
            */
        
            $table->decimal('base_price', 10, 2);
            
            // Pre-order handling
            $table->boolean('pre_order')->default(false);
            $table->string('pre_order_expectation')->nullable(); // e.g., "Available next month"
        
            // Seasonal availability
            $table->boolean('seasonal')->default(false);
            $table->date('season_start')->nullable();
            $table->date('season_end')->nullable();
        
            // // Optional weight-based pricing
            // $table->json('weight_pricing_rules')->nullable(); 
            // /*
            //     Example structure:
            //     [
            //         {"weight": "250g", "price": 5.00},
            //         {"weight": "500g", "price": 9.00}
            //     ]
            // */
        
            $table->decimal('weight', 8, 2)->nullable()->comment('Product weight in kilograms');
            $table->unsignedInteger('inventory')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
