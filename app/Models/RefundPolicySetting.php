<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundPolicySetting extends Model
{
    protected $fillable = [
        'refund_policy',
    ];

    protected $casts = [
        'refund_policy' => 'array',
    ];
}
