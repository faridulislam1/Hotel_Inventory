<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','contactNo', 'contactNo2', 'IP', 'email', 'cityCode', 'countryCode', 'addressLine1', 'addressLine2', 
        'pnr', 'bookingId', 'isDomestic', 'origin', 'destination', 'airlineCode', 'airlineRemark', 'isLCC', 
        'nonRefundable', 'invoiceAmount', 'invoiceNo', 'invoiceCreatedOn', 'fareRules', 'errorCode', 
        'errorMessage', 'isTicket', 'hold', 'offer', 'public', 'baseFare', 'otherFare', 'offerFare', 'publicFare', 
        'agent_public_price', 'agent_offer_price', 'agent_commission', 'agent_tds', 'agent_invoice_price', 
        'agent_net_receivable', 'account_fare', 'my_net_receivable', 'jdate', 'cancelID', 'voidID', 'RefID', 
        'ReissueID', 'SsrID', 'TicketId', 'CreditNoteNo', 'ChangeRequestStatus', 'updatedOn', 'addedOn', 
        'addedBy', 'agntCurrency', 'agency', 'acceptBy', 'lead_pax', 'surname', 'ppr', 'partial_pay', 
        'due_amt', 'request_for', 'ordertime', 'updatetime', 'copy', 'sourceID', 'void_pass', 'cancel_pass', 
        'cancel_leg', 'refund_pass', 'refund_leg', 'reissue_pass', 'ssr_leg', 'ssr_pass', 'sb_bag', 'remark_book', 
        'pcc_currency', 'flt_class', 'pass_copy', 'refund_point', 'reissue_point', 'passID', 'agent_bal', 
        'agent_crit', 'subID'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passengers()
{
    return $this->hasMany(Passenger::class, 'bookings_id'); // Specify the foreign key 'bookings_id'
}

    
}
