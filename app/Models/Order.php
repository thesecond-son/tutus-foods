<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 
        'customer_email', 
        'customer_first_name', 
        'customer_last_name',
        'customer_phone', 
        'customer_instagram', 
        'order_notes',
        'delivery_type', 
        'delivery_town', 
        'delivery_postcode', 
        'delivery_fee',
        'delivery_option_id', 
        'delivery_address',
        'pickup_location_id', 
        'pickup_date', 
        'pickup_time',
        'total_amount', 
        'payment_method', 
        'payment_status',
        'delivery_pickup_status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryOption()
    {
        return $this->belongsTo(DeliveryOption::class);
    }

    public function pickupLocation()
    {
        return $this->belongsTo(PickupLocation::class);
    }
}