<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $table = "mail_logs";
    protected $fillable = ['email','template_slug','subject','mail_body','status','error_message'];
}
