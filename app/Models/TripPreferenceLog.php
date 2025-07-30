<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripPreferenceLog extends Model
{
    protected $table = 'trip_preference_logs';
    protected $fillable = [
        'lead_id', 'step', 'question', 'answer'
    ];
}
