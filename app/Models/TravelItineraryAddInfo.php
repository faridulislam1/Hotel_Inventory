<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelItineraryAddInfo extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function agencyInfo()
    {
        return $this->hasOne(AgencyInfo::class);
    }

    public function customerInfo()
    {
        return $this->hasOne(CustomerInfo::class);
    }
}
