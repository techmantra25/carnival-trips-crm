<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadWhatsappMessage extends Model
{
    use HasFactory;

    protected $table = 'lead_whatsapp_messages';

    protected $fillable = [
        'lead_id',
        'template_id',
        'sent_by',
        'sent_at',
        'status',
    ];

    // Relationships
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function template()
    {
        return $this->belongsTo(WhatsappMessageTemplate::class, 'template_id');
    }

    public function sender()
    {
        return $this->belongsTo(Admin::class, 'sent_by');
    }
}
