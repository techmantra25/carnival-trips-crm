<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadUrlClick extends Model
{
    protected $table = 'lead_url_clicks';
   protected $fillable = [
        'lead_id',
        'lead_url_share_id',
        'entry_time',
        'exit_time',
        'click_count',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
