<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUsSettings extends Model
{
    protected $fillable = [
        'hero',
        'philisophy',
        'tutus_family',
        'story',
        'join_us',
        'info',
        'reviews',
    ];

    protected $casts = [
        'hero' => 'array',
        'philisophy' => 'array',
        'tutus_family' => 'array',
        'story' => 'array',
        'join_us' => 'array',
        'info' => 'array',
        'reviews' => 'array',
    ];
}
