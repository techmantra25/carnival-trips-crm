<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadFinalQuotation extends Model
{
    use HasFactory;

    protected $table = 'lead_final_quotations';

    protected $fillable = [
        'lead_id',
        'sended_lead_itinerary_id',
        'shared_by',
        'channel',
    ];

    /* ===================== Relationships ===================== */

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function sentItinerary()
    {
        return $this->belongsTo(
            SendedLeadItinerary::class,
            'sended_lead_itinerary_id'
        );
    }

    public function sharedByUser()
    {
        return $this->belongsTo(Admin::class, 'shared_by');
    }
}
