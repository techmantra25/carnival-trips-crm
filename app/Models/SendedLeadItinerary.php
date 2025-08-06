<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendedLeadItinerary extends Model
{
    protected $table = 'sended_lead_itineraries';
    protected $fillable = [
        'lead_id', 'itinerary_code', 'destination_id', 'hotel_category', 'itinerary_syntax', 'total_days', 'total_nights', 'stay_by_journey', 'itinerary_journey', 'total_cost', 'remarks', 'send_via', 'sent_by_admin_id', 'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'total_cost' => 'decimal:2',
        'send_via' => 'string',
    ];

     public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }
     public function destination()
    {
        return $this->belongsTo(State::class, 'destination_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'hotel_category', 'id');
    }
}
