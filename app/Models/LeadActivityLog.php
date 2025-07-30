<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadActivityLog extends Model
{
    protected $table = 'lead_activity_logs';
    protected $fillable = [
        'lead_id', 'updated_by', 'message'
    ];
}
