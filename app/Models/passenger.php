<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class passenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookings_id',
        'title', 
        'first_name', 
        'last_name', 
        'gender', 
        'nationality',
        'email', 
        'passport_num', 
        'passport_expiry_date', 
        'date_of_birth', 
        'type'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'bookings_id');
    }
}
