<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{
    protected $fillable = [
        'hero',
        'favourites',
        'signature_brands',
        'order_cutoff_times',
        'footer',
    ];

    protected $casts = [
        'hero' => 'array',
        'favourites' => 'array',
        'signature_brands' => 'array',
        'order_cutoff_times' => 'array',
        'footer' => 'array',
    ];
}
