<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryWeightRange extends Model
{
    use HasFactory;

    protected $fillable = ['delivery_option_id', 'min_weight', 'max_weight', 'rate'];

    public function deliveryOption()
    {
        return $this->belongsTo(DeliveryOption::class);
    }
}
