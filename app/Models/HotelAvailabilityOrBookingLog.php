<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class HotelAvailabilityOrBookingLog extends Model
{
    protected $table = 'hotel_availability_or_booking_logs';


    protected $fillable = [
        'itinerary_id', 'code', 'channel', 'type', 'sent_by'
    ];

    public function itinerary(){
        return $this->belongsTo(SendedLeadItinerary::class, 'itinerary_id', 'id');
    }

    public function sender(){
        return $this->belongsTo(Admin::class, 'sent_by', 'id');
    }
}
