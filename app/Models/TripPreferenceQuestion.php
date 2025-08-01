<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripPreferenceQuestion extends Model
{
    use HasFactory;
    protected $table = 'trip_preference_questions';

    protected $fillable = ['title', 'step'];

    public function options()
    {
        return $this->hasMany(TripPreferenceQuestionOption::class, 'trip_question_id', 'id');
    }

}
