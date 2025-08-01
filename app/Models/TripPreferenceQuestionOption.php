<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripPreferenceQuestionOption extends Model
{
    use HasFactory;
    protected $table = 'trip_preference_question_options';
    protected $fillable = ['trip_question_id', 'option_text'];

    public function question()
    {
        return $this->belongsTo(TripPreferenceQuestion::class, 'trip_question_id', 'id');
    }
}
