<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendedLeadItineraryDetail extends Model
{
    protected $table = 'sended_lead_itinerary_details';

    protected $fillable = [
        'sended_lead_itinerary_id',
        'route_service_summary_id',
        'hotel_id',
        'room_id',
        'cab_id',
        'header',
        'field',
        'value',
        'value_quantity',
        'price',
        'rate',
        'ticket_price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rate' => 'decimal:2',
        'ticket_price' => 'decimal:2',
    ];

    // Relationship with Itinerary model
     public function itinerary()
     {
         return $this->belongsTo(SendedLeadItinerary::class);
     }

    public function hotel(){
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }
    public function room(){
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
    public function cab(){
        return $this->belongsTo(Cab::class, 'cab_id', 'id');
    }
    public function route_service(){
        return $this->belongsTo(RouteServiceSummary::class, 'route_service_summary_id', 'id');
    }

}
