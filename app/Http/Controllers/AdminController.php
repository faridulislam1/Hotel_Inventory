<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Admin Dashboard',
        ]);
    }

    public function convertXmlToJson()
    {

        $url = 'https://api.lomadee.com/api/lomadee/reportTransaction?publisherId=22972277&token=dGhlcmFwbGFjZS5hcHBAZ21haWwuY29tOkBDZHYzMDExMDA=&startDate=01082022&endDate=01102022&eventStatus=1';
        $response = Http::get($url);
        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch XML data'], 500);
        }

        $xmlContent = $response->body();
        $xmlObject = simplexml_load_string($xmlContent);
        if ($xmlObject === false) {
            return response()->json(['error' => 'Failed to parse XML'], 500);
        }
        $jsonArray = json_decode(json_encode($xmlObject), true);
        $jsonResponse = json_encode($jsonArray);
        return response($jsonResponse, 200)->header('Content-Type', 'application/json');
    }

}
