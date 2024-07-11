<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonName extends Model
{
    use HasFactory;

    protected $fillable = ['NameNumber', 'PassengerType', 'GivenName', 'Surname'];

}
