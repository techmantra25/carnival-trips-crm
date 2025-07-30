<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use Notifiable,SoftDeletes;

    // Define the table name if it doesn't follow Laravel's naming convention
    protected $table = 'admins';
    protected $dates = ['deleted_at'];
    
    // Allow mass assignment for these attributes
    protected $fillable = [
        'name', 'email', 'password', 'role','phone', 'designation_id', 'status','whatsapp','team_lead','address'
    ];

    // Define the hidden attributes (e.g., password, remember_token)
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Set the default password hashing
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Optionally, you can define custom methods related to roles or permissions
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    public function destinations()
    {
        return $this->belongsToMany(State::class, 'employee_destination', 'employee_id', 'state_id');
    }
   // Get direct subordinates (employees reporting to this admin)
    public function subordinates()
    {
        return $this->hasMany(Admin::class, 'team_lead');
    }

    // Get the admin's immediate leader (their team lead)
    public function leader()
    {
        return $this->belongsTo(Admin::class, 'team_lead');
    }

    // Scope to get only Team Leads (those who report to a specific admin like id = 16)
    public function scopeTeamLeadsUnder($query, $leaderId)
    {
        return $query->where('team_lead', $leaderId);
    }
    public function assignDestination(){
        return $this->hasMany(EmployeeDestination::class, 'employee_id');
    }
}
