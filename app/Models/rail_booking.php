<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rail_booking extends Model
{
    use HasFactory;

    protected $table = 'rail_bookings';

    protected $fillable = [
        'user_id',
        'pnr',
        'bookingId',
        'jstatus',
        'origin',
        'destination',
        'dtime',
        'atime',
        'train_no',
        'train_name',
        'jdate',
        'class',
        'isAC',
        'invoiceNo',
        'isTicket',
        'offer',
        'public',
        'baseFare',
        'currency',
        'offerFare',
        'publicFare',
        'agent_public_price',
        'agent_offer_price',
        'agent_commission',
        'agent_tds',
        'agent_invoice_price',
        'agent_net_receivable',
        'rail_accFare',
        'book_status',
        'curr_status',
        'ttime',
        'distance',
        'source',
        'rail_status',
        'remark',
        'updatedOn',
        'addedOn',
        'addedBy',
        'acceptBy',
        'sourceID',
        'availabl',
        'agent_bal',
        'agent_crit',
    ];
}
