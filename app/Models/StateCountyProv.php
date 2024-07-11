<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StateCountyProv extends Model
{
    use HasFactory;

    protected $fillable = ['StateCode','agency_info_id'];

// StateCountyProv.php

public function address()
{
    return $this->belongsTo(Address::class, 'agency_info_id');
}

}
