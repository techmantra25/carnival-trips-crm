<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HotelAvailabilityRequest extends Model
{
    protected $table = 'hotel_availability_requests';


    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'sent_by', 'id');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }
}
