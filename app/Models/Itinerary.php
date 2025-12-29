<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Itinerary extends Model
{
    use HasFactory;
   protected $table = "itineraries";
   protected $fillable = [
        'type', 'destination_id', 'lead_id', 'hotel_category', 'total_days', 'total_nights', 'itinerary_journey', 'night_journey', 'divisions_journey','itinerary_syntax','stay_by_journey','valid_from','valid_to'
   ];

    public function destination()
    {
        return $this->belongsTo(State::class, 'destination_id');
    }
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
    public function hotelCategory()
    {
        return $this->belongsTo(Category::class, 'hotel_category', 'id');
    }
    public function details(){
        return $this->hasMany(ItineraryDetail::class, 'itinerary_id', 'id');
    }
}
