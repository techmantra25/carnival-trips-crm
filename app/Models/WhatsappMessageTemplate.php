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
        'name',
        'language',
        'category',
        'temp_Id',
        'allow_category_change',
        'Status',
        'components',
        'Sno',
    ];

}
