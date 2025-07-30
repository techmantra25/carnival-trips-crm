<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDestination extends Model
{
    protected $table = 'employee_destination'; // specify the pivot table name if it's not plural

    public $timestamps = false; // optional: if your pivot table doesn't have created_at/updated_at

    protected $fillable = [
        'employee_id',
        'state_id',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
