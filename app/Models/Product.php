<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'name', 'slug', 'description', 'allergens', 'image_path', 
        'category_id', 'product_options', 'base_price', 'weight','pre_order',
        'pre_order_expectation', 'seasonal', 'season_start', 'season_end',
        'inventory', 'active', 'pickup_only'
    ];

    protected $casts = [
        'product_options' => 'array',
        // 'weight_pricing_rules' => 'array',
        'seasonal' => 'boolean',
        'pre_order' => 'boolean',
        'active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    // Change from belongsTo to belongsToMany, but keep the name as 'category'
    // public function category()
    // {
    //     return $this->belongsToMany(Category::class);
    // }

    //     public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Optional: Keep a helper for the primary category if needed
    public function primaryCategory()
    {
        return $this->categories()->first();
    }
}
