<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;


class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
    ];

    // public function cities()
    // {
    //     return $this->hasMany(City::class);
    // }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    
}
