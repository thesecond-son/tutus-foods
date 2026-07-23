<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutPageSetting extends Model
{
    protected $fillable = [
        'shipping_notice',
    ];
 
    /**
     * Always return the single settings row, creating it if it doesn't exist.
     */
    public static function instance(): static
    {
        return static::firstOrCreate([], [
            'shipping_notice' => 'We post orders from Monday to Thursday only. Orders received after 10am on Thursday will be posted on Monday: Order by Thursday before 10am.',
        ]);
    }
}
