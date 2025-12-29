<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonServicesHotel extends Model
{
    protected $table = "non_services_hotel";
    protected $fillable = [
        'city_id','category_id', 'hotel_name', 'address', 'image'
    ];

    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
