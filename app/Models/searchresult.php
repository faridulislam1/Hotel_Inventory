<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class searchresult extends Model
{
    use HasFactory;

    protected $fillable = [
        'country', 'check_in', 'check_out', 'guests'
    ];

    protected $casts = [
        'guests' => 'array', 
    ];
}
