<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'available_days',
        'start_time',
        'end_time',
        'interval_minutes',
    ];

    protected $casts = [
        'available_days' => 'array',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Generate pickup time slots based on the configured start, end, and interval.
     *
     * @return array
     */
    public function generateTimeSlots(): array
    {
        if (!$this->start_time || !$this->end_time) {
            return [];
        }

        $slots = [];
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        $interval = $this->interval_minutes * 60;

        for ($time = $start; $time < $end; $time += $interval) {
            $slots[] = date('H:i', $time);
        }

        return $slots;
    }
}
