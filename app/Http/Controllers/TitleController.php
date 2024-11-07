<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Title;
use Illuminate\Support\Facades\Auth; 
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Goutte\Client;

class TitleController extends Controller
{
    public static $categories, $category;
    public function __construct()
    {
        $this->middleware('auth:api', options: ['except' => ['login', 'register']]);
    }

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
   
}
