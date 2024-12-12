<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotelbooking extends Model
{
    use HasFactory;
    protected $fillable = ['hotel_id', 'room_id', 'rooms_booked', 'booking_type', 'customer_name', 'customer_email', 'customer_phone', 'check_in_date', 'check_out_date', 'payment_status','inventory','sales'];


    protected $casts = [
        'inventory' => 'array',
        'sales' => 'array',
    ];
    
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
