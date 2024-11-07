<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking_agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent',
        'amount',
        'currency',
        'pnr',
        'bookingID',
        'flight_no',
        'tot_time',
        'origin',
        'date_time_orig',
        'destination',
        'date_time_dest',
        'layover',
        'layover_airport',
        'date_time_layover',
        'isLCC',
        'Refundable',
        'journey_type',
        'adult',
        'child',
        'infant',
        'ptitle',
        'pfname',
        'plname',
        'gender',
        'dob',
        'passportno',
        'passportexpdate',
        'contactNo',
        'email',
        'cityCode',
        'countryCode',
        'addressLine1',
        'addressLine2',
        'updatedOn',
        'addedOn',
        'addedBy',
    ];
}
