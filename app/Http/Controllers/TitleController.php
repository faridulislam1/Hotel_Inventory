<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Title;
use Illuminate\Support\Facades\Auth; 
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Goutte\Client;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TitleController extends Controller
{
    public static $categories, $category;
    // public function __construct()
    // {
    //     $this->middleware('auth:api', options: ['except' => ['login', 'register']]);
    // }

    protected $product;
    private $results = array();

    
    public function scraper()
    {
        $client = new Client();
        $url = 'https://www.worldometers.info/coronavirus/';
        $page = $client->request('GET', $url);
        $page->filter('#maincounter-wrap')->each(function ($item) {
            $this->results[$item->filter('h1')->text()] = $item->filter('.maincounter-number')->text();
        }); 
        // $page=$cliient1->request('GET',$ur l); 
        $data = $this->results;
        return view('scraper', compact('data'));
    }

    // public function rete(){
    //     $client=new Client();
    //     $url="https://www.worldometers.info/coronavirus/";
    //     $page=$client->rerquest('GET',$url);
    // }

    
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);
    
    //     $credentials = $request->only('email', 'password');
    
    //     if (!Auth::attempt($credentials)) {
    //         return response()->json([
    //             'message' => 'Invalid email or password',
    //         ], 401);
    //     }  
    //     $user = Auth::user();
    //     $user->makeHidden(['created_at', 'updated_at']);
    //     return response()->json([
    //         'user' => [
    //             'email' => $user->email,
    //             'role' => $user->role,
    //             'token' => Auth::tokenById($user->id),
    //         ],
    //         'message' => 'Successfully logged in',
    //     ]);
    // }


    public function store()
    {
        $categories = Title::all()->map(function ($category) {
            $categoryArray = $category->toArray();
            unset($categoryArray['created_at']);
            unset($categoryArray['updated_at']);
            return $categoryArray;
        });
    
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
    public function create(Request $request)
    {
        Title::newCategory($request);
        return response()->json([
            'message' => 'Title created successfully',
           
        ]);
    } 

    public function destroy(Request $request)
    {
        $roomId = $request->id;
        $room = Title::find($roomId);
        if (!$room) {
            return response()->json(['message' => 'Room with ID ' . $roomId . ' not found'], 404);
        }
        Title::destroy($roomId);
        return response()->json(['message' => 'Title delet ed successfully'], 200);
    } 
    public function updates(Request $request)  
    {
        Title::updates($request);
        return response()->json(['message' => 'Title Updated successfully'], 200);

    }

    public function fetchIPData($ip)
    {
        $apiKey = '05D4015FA6DB5903B6E18B7BCD4C62F6';
        $url = "https://api.ip2location.io/?key=$apiKey&ip=$ip";

        try {
           
            $response = Http::get($url);

            if ($response->successful()) { 
                $data = $response->json();
                return response()->json($data); 
            } else {
               
                return response()->json(['error' => 'Failed to fetch IP data'], $response->status());
            }
        } catch (\Exception $e) {
         
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function fetchRailData()
    {
        try {
            
            $url = env('RAIL_API_URL', 'https://tbpdmc.com/Rail/irctc/rest/rail/irctc/rest/');
            $postData = [
                "userName" => "TBP786@05",
                "password" => "bd@555@2421",
                "action" => "login"
            ];
    
            $response = Http::post($url, $postData);
           
            if ($response->successful()) {
                return response()->json($response->json());
            }
    
            // Handle error if not successful
            $status = $response->status();
            $body = $response->body();
            
            \Log::error("Failed to fetch data from API. Status: $status, Response: $body");
    
            return response()->json(['error' => 'Failed to fetch data', 'details' => $body], $status);
        } catch (\Exception $e) {

            
            // Return a generic error message with exception details
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    public function searchAirport(Request $request)
    {
        $validated = $request->validate([
            'searchTerm' => 'required|string',
        ]);

        $bearerToken = $request->header('Authorization');
        if (!$bearerToken) {
            return response()->json([
                'message' => 'The authorization token is required.'
            ], 400);
        }

        $apiUrl = 'https://cloud.travelbusinessportal.com/tbp_live/test1/searchAirport';

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => $bearerToken,
        ])->post($apiUrl, [
            'searchTerm' => $validated['searchTerm'],
        ]);
        if ($response->successful()) {
            $data = $response->json();
            foreach ($data as $airport) {
                if (isset($airport['iata']) && strtoupper($airport['iata']) === strtoupper($validated['searchTerm'])) {
                    return response()->json($airport);
                }
            }
            return response()->json(['error' => 'No matching airport found'], 404);
        } else {
            return response()->json(['error' => 'Failed to fetch data from external API.'], 500);
        }
    }
    
}
