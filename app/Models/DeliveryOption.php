<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOption extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'calculated_by', 'rate'];

    public function weightRanges()
    {
        return $this->hasMany(DeliveryWeightRange::class);
    }
}

