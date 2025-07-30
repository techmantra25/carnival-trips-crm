<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItineraryTemplateDetail extends Model
{
    use HasFactory;

    protected $table = "itinerary_template_details";

    protected $fillable = [
        'itinerary_template_id',
        'header',
        'field',
        'value',
        'description',
        'price',
    ];
     // Relationship with Itinerary model
     public function itinerary()
     {
         return $this->belongsTo(Itinerary::class);
     }
}
