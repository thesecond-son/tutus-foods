<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivacyPolicySetting extends Model
{
    protected $fillable = [
        'privacy_policy',
    ];

    protected $casts = [
        'privacy_policy' => 'array',
    ];
}
