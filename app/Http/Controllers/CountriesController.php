<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\counties;


class CountriesController extends Controller
{
    //

    public function getAllCountries()
    {
        $countries = counties::all();
         $groupedCountries = $countries->groupBy('name');
        
        $formattedCountries = $groupedCountries->map(function ($countryGroup) {
            $codes = $countryGroup->map(function ($country) {
                return $country->code;
            })->toArray();
            
            return [
                'countryname' => $countryGroup->first()->name,
                'SB' => $codes[0] ?? null, 
                'TP' => implode(',', array_slice($codes, 1)) 
            ];
        });
        return response()->json($formattedCountries);
    }
 
}


