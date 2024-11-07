<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activity_booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'actID',
        'bookingId',
        'invoice_number',
        'country',
        'currency',
        'date_from',
        'lead_pass_name',
        'lead_pass_email',
        'lead_pass_country',
        'lead_pass_mobile',
        'adult0', 'adult1', 'adult2', 'adult3', 'adult4',
        'adult5', 'adult6', 'adult7', 'adult8', 'adult9', 'adult10',
        'child0', 'child1', 'child2', 'child3', 'child4',
        'child5', 'child6', 'child7', 'child8',
        'infant0', 'infant1', 'infant2', 'infant3', 'infant4',
        'infant5', 'infant6', 'infant7', 'infant8',
        'isTour', 'offer', 'public', 'baseFare', 'offerFare', 'publicFare',
        'agent_public_price', 'agent_offer_price', 'agent_commission',
        'agent_tds','agent_invoice_amount', 'agent_net_receivable',
        'cancel_date', 'cancel_status', 'updatedOn', 'addedOn',
        'addedBy', 'agent_id', 'flight_info', 'flight_ticket', 'remarks',
        'new_travel_date', 'new_pickup_location', 'admin_status',
        'refund_amount', 'vendor', 'services', 'net_cost',
    ];
}
