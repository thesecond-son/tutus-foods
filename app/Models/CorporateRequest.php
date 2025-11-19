<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateRequest extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'purpose',
        'people',
        'type',
        'requests',
    ];
}
