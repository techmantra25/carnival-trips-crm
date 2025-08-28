<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WhatsappMessageTemplate extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_message_templates';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'type',
        'body',
        'status',
        'image',
        'external_link',
    ];

    /**
     * Optional: cast type for convenience
     */
    protected $casts = [
        'type' => 'string',
        'status' => 'string',
    ];
}
