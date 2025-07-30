<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_type',
        'room_category',
        'room_name',
        'no_of_rooms',
        'no_of_beds',
        'extra_bed',
        'mattress',
        'capacity',
        'ammenities',
        'positions',
        'is_priority',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}

