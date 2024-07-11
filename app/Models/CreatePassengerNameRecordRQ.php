<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatePassengerNameRecordRQ extends Model
{
    use HasFactory;

    protected $fillable = ['version', 'targetCity', 'haltOnAirPriceError'];

    public function travelItineraryAddInfo()
    {
        return $this->hasOne(TravelItineraryAddInfo::class);
    }
}
