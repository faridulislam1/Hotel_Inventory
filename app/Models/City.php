<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Country;
use App\Models\room;
use App\Models\hotel;


class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'country_id',
    ]; 
 
    // public function country()
    // {
    //     return $this->belongsTo(Country::class);
    // }

    // public function hotels()
    // {
    //     return $this->hasMany(hotel::class);
    // }

    
    // public function rooms()
    // {
    //     return $this->hasMany(room::class);
    // }

    // public function country()
    // {
    //     return $this->belongsTo(Country::class);
    // }
    // public function hotels()
    // {
    //     return $this->hasMany(hotel::class);
    // }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
   
}
