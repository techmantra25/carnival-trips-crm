<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'leads';
    protected $fillable = [
        'unique_id', 'generate_from', 'nationality_type', 'customer_name', 'customer_email', 'customer_mobile', 'country_code', 'customer_whatsapp', 'travel_location', 'hotel_category', 'travel_duration', 'travel_in_days', 'travel_in_nights', 'travel_duration_text', 'departure_date', 'arrival_date', 'number_of_adults', 'number_of_children', 'children_data', 'number_of_travellor', 'number_of_rooms', 'extra_mattress', 'meal_type', 'source_type', 'lead_source', 'created_by', 'team_lead_id', 'assigned_to_id', 'package_type', 'status'
    ];

    // public function city(): BelongsTo
    // {
    //     return $this->belongsTo(City::class, 'travel_location', 'id');
    // }
    public function destination(): BelongsTo
    {
        return $this->belongsTo(State::class, 'travel_location', 'id');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'hotel_category', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function teamLead()
    {
        return $this->belongsTo(Admin::class, 'team_lead_id');
    }

    public function assignee()
    {
        return $this->belongsTo(Admin::class, 'assigned_to_id');
    }
    public function itinerary()
    {
       return $this->hasOne(Itinerary::class, 'lead_id', 'id');
    }
    public function sent_itinerary()
    {
       return $this->hasMany(SendedLeadItinerary::class, 'lead_id', 'id');
    }

}
