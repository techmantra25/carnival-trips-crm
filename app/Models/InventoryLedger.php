<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLedger extends Model
{
    protected $table = 'inventory_ledger';

    protected $fillable = [
        'inventory_id',
        'hotel_id',
        'room_id',
        'lead_id',
        'inventory_date',
        'entry_type',
        'quantity',
        'description',
        'created_by',
    ];

    protected $casts = [
        'inventory_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Inventory (main parent)
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    // Hotel
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    // Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // Lead (booking) - nullable
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    // User (who performed action) - nullable
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Filter by date
    public function scopeForDate($query, $date)
    {
        return $query->where('inventory_date', $date);
    }

    // Filter by hotel
    public function scopeForHotel($query, $hotelId)
    {
        return $query->where('hotel_id', $hotelId);
    }

    // Filter by room
    public function scopeForRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    // Filter by entry type
    public function scopeOfType($query, $type)
    {
        return $query->where('entry_type', $type);
    }
}
