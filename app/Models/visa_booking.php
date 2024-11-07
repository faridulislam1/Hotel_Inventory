<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class visa_booking extends Model
{
    use HasFactory;

    protected $table = 'visa_bookings'; 
    protected $fillable = [
        'user_id',
        'ref_no',
        'country',
        'visa_type',
        'entry_type',
        'visa_duration',
        'processing_duration',
        'visa_charge',
        'require_document',
        'jstatus',
        'visa_status',
        'delivary_date',
        'acceptBy',
        'remark',
        'updatedOn',
        'addedOn',
        'addedBy'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
