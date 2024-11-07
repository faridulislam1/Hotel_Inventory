<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotel_booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bookingId',
        'invoice_number',
        'confirmation_no',
        'booking_ref_no',
        'HCN',
        'HotelCity',
        'hotel_name',
        'checkIn',
        'checkOut',
        'jdate',
        'offer',
        'public',
        'base',
        'base_offerFare',
        'publicFare',
        'offerFare',
        'agent_pFare',
        'agent_oFare',
        'agent_commission',
        'agent_tds',
        'agent_invoice_amount',
        'agent_net_receivable',
        'hotel_account_fare',
        'currency',
        'title',
        'fname',
        'lname',
        'email',
        'mobile',
        'LastCancellationDate',
        'ChangeRequestId',
        'ChangeRequestdate',
        'updatedOn',
        'addedOn',
        'addedBy',
        'sourceID',
        'agent_bal',
        'agent_crit',
    ];
}
