<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInfo extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function contactNumbers()
    {
        return $this->hasOne(ContactNumbers::class);
    }

    public function personName()
    {
        return $this->hasOne(PersonName::class);
    }
}
