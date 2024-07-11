<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rooms;

class itenary extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'country_id',
        'hotel_id' ,
        'city_id',
        'room_num',
        'available_capacity' ,
        'max_capacity',
        'refundable',
        'non_refundable',
        'refundable_break',
        'refundable_nonbreak' ,
        'room_size',
        'cancellation_policy',
        'room_available',
        'extra_bed' ,
        'room_facilities' ,
        'bed_type',

    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // public function rooms()
    // {
    //     return $this->hasMany(Rooms::class);
    // }
    public function hotel(){
    return $this->belongsTo(Hotel::class);
   }

   public function city()
    {
        return $this->belongsTo(City::class);
    }

  
    public function rooms()
    {
        return $this->hasMany(Rooms::class);
    }
}
