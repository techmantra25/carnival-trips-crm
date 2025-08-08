<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadUrlShare extends Model
{
    protected $table = 'lead_url_shares';
    protected $fillable = [
        'lead_id',
        'itinerary_id',
        'sended_lead_itinerary_id',
        'shared_by',
        'channel',
        'is_cron',
        'remarks',
        'links',
        'shared_at',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
    public function sent_itinerary()
    {
        return $this->belongsTo(SendedLeadItinerary::class, 'sended_lead_itinerary_id', 'id');
    }

    public function sharedBy()
    {
        return $this->belongsTo(Admin::class, 'shared_by');
    }
}
