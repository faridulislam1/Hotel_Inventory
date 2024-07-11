<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['AddressLine', 'CityName', 'CountryCode', 'PostalCode', 'StreetNmbr'];

   // Address.php

public function stateCountyProv()
{
    return $this->hasMany(StateCountyProv::class, 'agency_info_id');
}

}
