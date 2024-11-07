<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\new_user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use App\Models\hotel;
use App\Models\room;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\City;
use App\Models\Rooms; 
use App\Models\Address;
use App\Models\ContactNumbers;
use App\Models\StateCountyProv;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;
use App\Models\otherImage;

// require_once public_path('php-jwt/JWT.php');
// require_once public_path('php-jwt/BeforeValidException.php');
// require_once public_path('php-jwt/ExpiredExceptio n.php');
// require_once public_path('php-jwt/SignatureInvalidException.php');

 class AuthController extends Controller
{
    private static $hotel, $countries, $name, $product, $image, $imageName, $directory, $imageUrl, $otherImage, $imageExtension;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'emailID' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        // Retrieve the credentials from the request
        $credentials = $request->only('emailID', 'password');
    
        // Attempt to authenticate the user
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }
        // Get the authenticated user
        $user = Auth::user();
        $user->makeHidden(['created_at', 'updated_at']); // Hide timestamps if needed
    
        return response()->json([
            'user' => [
                'emailID' => $user->emailID, 
                'country' => $user->country,
                'division' => $user->division,
                'district' => $user->district, 
                'address' => $user->address,
                'contact_no' => $user->contact_no, // Add contact_no field
                'company_name' => $user->company_name, // Add company_name field
                'company_persons_name' => $user->company_persons_name, // Add company_persons_name field
                'currency' => $user->currency, // Add currency field
                'balance' => $user->balance, // Add balance field
                'credit_balance' => $user->credit_balance, // Add credit_balance field
                'status' => $user->status, // Add status field
                'token' => Auth::tokenById($user->id), // Token for the authenticated user
            ],
            'message' => 'Successfully logged in',
        ]);
    }
    
        public function register(Request $request)
        {
            // Validate the request
            $request->validate([
                'emailID' => 'required|string|email|max:255|unique:users,emailID,' . $request->emailID,
                'address' => 'nullable|string|max:255',
                'division' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'company_persons_name' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'contact_no' => 'nullable|string|max:15', // Adjust max length as needed
                'currency' => 'nullable|string|max:3', // Currency typically short (e.g., BDT)
                'balance' => 'nullable|numeric', // Balance should be numeric
                'credit_balance' => 'nullable|numeric', // Credit balance should be numeric
                'status' => 'nullable|string|max:20', // Status should have a reasonable length
                'password' => 'required|string|min:8', // Password must be present and at least 8 characters long
            ], [
                'emailID.unique' => 'The email has already been taken.',
            ]);
        
            // Create the user
            $user = User::create([
                'emailID' => $request->emailID,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'division' => $request->division,
                'country' => $request->country,
                'company_name' => $request->company_name,
                'company_persons_name' => $request->company_persons_name,
                'district' => $request->district,
                'contact_no' => $request->contact_no,
                'currency' => $request->currency,
                'balance' => $request->balance ?? 0.00, // Default balance to 0 if not provided
                'credit_balance' => $request->credit_balance ?? 0.00, // Default credit balance to 0 if not provided
                'status' => $request->status ?? 'active', // Default status to 'active' if not provided
                'password' => bcrypt($request->password),
                // 'role' => $request->role ?? 'user', // Uncomment if roles are required
            ]);
        
            return response()->json([
                'message' => 'User created successfully',
                // 'user' => $user // Uncomment if you want to return user details
            ]);
        }
        
        
// public function login1(Request $request)
// {
//     $request->validate([
//         'emailID' => 'required|string|email',
//         'password' => 'required|string',
//     ]);

//     $credentials = $request->only('emailID', 'password');

//     if (!Auth::attempt($credentials)) {
//         return response()->json([
//             'message' => 'Invalid email or password',
//         ], 401);
//     }

//     $user = Auth::user();
//     $user->makeHidden(['created_at', 'updated_at']);

//     return response()->json([
//         'user' => [
//             'emailID' => $user->emailID,
//             //'role' => $user->role,
//             'token' => Auth::tokenById($user->id),
//         ],
//         'message' => 'Successfully logged in',
//     ]);
// }

public function register1(Request $request)
{
    // Validate the request
    // $request->validate([ 
    //     'name' => 'required|string|max:255',
    //     'emailID' => 'required|string|email|max:255|unique:new_users',
    //     'password' => 'required|string|min:6',
    //     'mobile' => 'required|string',
    //     'country' => 'nullable|string',
    //     'division' => 'nullable|string',
    //     'address' => 'nullable|string',
    // ]);

    // Create the new user
    $user = new_user::create([
        'name' => $request->name,
        'emailID' => $request->emailID,
        'password' => bcrypt($request->password),
        'mobile' => $request->mobile,
        'country' => $request->country,
        'division' => $request->division,
        'address' => $request->address,
    ]);

    return response()->json([
        'message' => 'User created successfully',
        'user' => $user
    ]);
}

    public function storehotel()
    {
        $countries = Country::all();
        $cities = City::all();
        $rooms = room::all();

        return view('admin.product.add-product', [
            'countries' => $countries,
            'cities' => $cities,
            'rooms' => $rooms,
        ]);
    }
    // public function manageProduct()
    // {
    //     $countries = Country::with(['cities.hotels', 'hotels.rooms'])->get();
    //     return response()->json($countries);
       
    public function manageProduct(Request $request)
    {
        // $token = $request->header('Authorization');
        // if (!empty($token)) {
        //     if ($this->verify_jwt_token($token)) {
        //         try {
                    // Set custom headers
                    
                    $countries = Country::with(['cities.hotels.rooms'])->get();
                    
                    // Loop through each country and hide the specified fields
                    $countries->each(function ($country) { 
                        $country->makeHidden(['id', 'created_at', 'updated_at']);
                        $country->cities->each(function ($city) {
                            $city->makeHidden(['id', 'created_at', 'updated_at', 'country_id', 'city_id']);
                            $city->hotels->each(function ($hotel) {
                                $hotel->makeHidden(['id', 'created_at', 'updated_at', 'country_id', 'city_id', 'hotel_id']);
                                $hotel->rooms->each(function ($room) {
                                    $room->makeHidden(['id', 'created_at', 'updated_at', 'hotel_id', 'city_id']);
                                });
                            });
                        });
                    });

                    return response()->json($countries);
        //         } catch (\Exception $ex) {
        //             // Log or handle exceptions
        //             return response()->json(['error' => 'An error occurred: ' . $ex->getMessage()], 500);
        //         }
        //     } else {
        //         return response()->json(['error' => 'Unauthorized'], 401);
        //     }
        // } else {
        //     return response()->json(['error' => 'Authorization header is missing'], 401);
        // }
    }
    public static function getImageUrl($request)
    {
        $image = $request->file('Single_image');

        if ($image) {
            $directory = 'upload/product-images/';
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move($directory, $imageName);
            $imageUrl = $directory . $imageName;
            return $imageUrl;
        }

        return null;
    }
   
    
    public function savehotel(Request $request)
    {
        $country = Country::create([
            'country' => $request->country,
        ]);

        $city = City::create([
            'city' => $request->city,
            'country_id' => $country->id,
        ]);

        $extraBedValue = $request->input('extra_bed');
        if ($extraBedValue === null || $extraBedValue === '') {
            $extraBedValue = 'No';
        }

        $singleImageUrl = self::getImageUrl($request);
        //$multipleImageUrls = self::newOtherImage($request);

        $multipleImageUrls = [];
        if ($request->hasFile(key: 'other_image')) {
            $multipleImageUrls = $this->newOtherImages($request->file('other_image'));
        }
    
        // Create Hotel
        Hotel::create([
            'hotel' => $request->hotel,
            'city_id' => $city->id,
            'embed_code' => $request->embed_code,
            'landmark' => $request->landmark,
            'rating' => $request->rating,
            'address' => $request->address,
            'highlights' => $request->highlights,
            'long_decription' => $request->long_decription,
            'currency' => $request->currency,
            'term_condition' => $request->term_condition,
            'longitude' => $request->longitude,
            'litetitude' => $request->litetitude,
            'single_image' => $singleImageUrl,
            'multiple_image' => !empty($multipleImageUrls) ? serialize($multipleImageUrls) : null,
            'facilities' => is_array($request->facilities) ? implode(',', $request->facilities) : $request->facilities,
        ]);
    
        return response()->json(['message' => 'Hotel Data successfully inserted'], 201);
    }
  
    public function search(Request $request)
    {
        $query = $request->input('query');
        $cities = City::where('city', 'like', "%$query%")->get();
        $cities->makeHidden(['id', 'created_at', 'updated_at', 'country_id']);
        $countries = Country::where('country', 'like', "%$query%")->get();
        $countries->makeHidden(['id', 'created_at', 'updated_at']);
        $hotels = hotel::where('hotel', 'like', "%$query%")->get();
        $hotels->makeHidden(['id', 'created_at', 'updated_at', 'country_id', 'city_id']);
        $rooms = room::where('room_num', 'like', "%$query%")->get();
        $rooms->makeHidden(['id', 'created_at', 'updated_at', 'hotel_id', 'city_id']);
        $results = [];

        if ($cities->isNotEmpty()) {
            $results['cities'] = $cities;
        }

        if ($countries->isNotEmpty()) {
            $results['countries'] = $countries;
        }

        if ($hotels->isNotEmpty()) {
            $results['hotels'] = $hotels;
        }

        if ($rooms->isNotEmpty()) {
            $results['rooms'] = $rooms;
        }

        if (empty($results)) {
            return response()->json(['error' => 'No results found'], 404);
        }

        return response()->json($results);
    }

    public function show($id)
    {
        $country = Country::with([
            'cities' => function ($query) {
                $query->with([
                    'hotels' => function ($query) {
                        $query->with([
                            'rooms'
                        ])->select(['id', 'city_id', 'country_id', 'hotel', 'embed_code', 'landmark', 'rating', 'Single_image', 'multiple_image', 'address', 'highlights', 'long_decription', 'currency', 'term_condition', 'longitude', 'litetitude', 'facilities']);
                    }
                ])->select(['id', 'country_id', 'city', 'created_at', 'updated_at']);
            }
        ])->find($id, ['id', 'country', 'created_at', 'updated_at']);

        if (!$country) {
            return response()->json([
                'status' => 404,
                'message' => "No such country found"
            ], 404);
        }

        // Hide fields from the response
        $country->makeHidden(['status', 'id', 'country_id']);
        foreach ($country->cities as $city) {
            $city->makeHidden(['id', 'country_id']);
            foreach ($city->hotels as $hotel) {
                $hotel->makeHidden(['id', 'city_id', 'country_id']);
                foreach ($hotel->rooms as $room) {
                    $room->makeHidden(['id', 'hotel_id', 'city_id']);
                }
            }
        }

        return response()->json([
            'country' => $country
        ], 200);
    }

    public function showroom($id)
    {
        $country = room::with('rooms')->find($id);

        if ($country) {
            return response()->json([
                'status' => 200,
                'country' => $country
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No such room found"
            ], 404);
        }
    }

    // public function show($id)
    // {
    //     $student = Country::find($id);
    //     if ($student) {
    //         return response()->json([
    //             'status' => 200,
    //             'student' => $student
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => "no such hotelinfo found"
    //         ], 404);
    //     }
    // }

    public function productDelete(Request $request)
    {
        Hotel::deleteProduct($request->id);
        return back()->with('message', 'Info deleted');
    }  
  

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    public function delete(Request $request)
    {
        Hotel::delete($request->id);
        return back()->with('message', 'Info deleted');
    }

    // Room Info

    public function storeroom()
    {

        $countries = Country::all();
        $cities = City::all();
        //$cities = City::with('country')->get();
        $rooms = room::with('city.country')->get();
        return view('admin.country.add-product', compact('countries', 'cities', 'rooms'));
    } 

    public function getSubCategoryByCategory()
    {

        return response()->json(City::where('country_id', $_GET['id'])->get());
    }

    public function hotel()
    {

        return response()->json(hotel::where('city_id', $_GET['id'])->get());
    }

    // public function manageroom(){

    //    //$products=itenary::with('city')->get();
    //      $products=room::all();
    //      $products->makeHidden(['created_at', 'updated_at']);
    //    return response()->json($products);
    //     //exit();
    //     return view('admin.country.manage-product',[
    //         'products'=>room::all()
    //     ]);
    //     // return view('admin.product.manage', ['products' => Product::all()]);
    //     //dd($products);
    // }

    public function manageroom(Request $request)
    {
        $token = $request->header('Authorization');
        if (!empty($token)) {
            if ($this->verify_jwt_token($token)) {
                try {
                    //$products=itenary::with('city')->get();
                    $products = room::all();
                    $products->makeHidden(['created_at', 'updated_at']);
                    return response()->json($products);
                    //exit();
                    return view('admin.country.manage-product', [
                        'products' => room::all()
                    ]);
                    // return view('admin.product.manage', ['products' => Product::all()]);
                    //dd($products);


                } catch (\Exception $ex) {
                    // Log or handle exceptions
                    return response()->json(['error' => 'An error occurred: ' . $ex->getMessage()], 500);
                }
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['error' => 'Authorization header is missing'], 401);
        }
    }

    public function saveroom(Request $request)
    {
        // dd($request);


        $validator = Validator::make($request->all(), [


            'city_id' => 'required',
            'room_num' => 'required',
            'available_capacity' => 'required',
            'max_capacity' => 'required',
            'refundable' => 'required',
            'non_refundable' => 'required',
            'refundable_break' => 'required',
            'refundable_nonbreak' => 'required',
            'room_size' => 'required',
            'cancellation_policy' => 'required',
            'room_available' => 'required',
            'extra_bed' => 'required',
            'room_facilities' => 'required',
            'bed_type' => 'required',
        ]);


        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $extraBedValue = $request->input('extra_bed');
        if ($extraBedValue === null || $extraBedValue === '') {
            $extraBedValue = 'No';
        }

        $product = (object)[
            'id' => $request->room_id,
        ];


        $roomDetails = [];

        $roomNumArray = is_array($request->room_num) ? $request->room_num : [$request->room_num];
        $products = count($roomNumArray);

        for ($i = 0; $i < $products; $i++) {
            $roomDetails[] = [
                // 'room_id' => $product->id,
                // 'country_id' => $request->country_id,
                //'hotel_id' => $hotel->hotel_id,
                'hotel_id' => $request->hotel_id,
                'city_id' => $request->city_id,
                'room_num' => isset($roomNumArray[$i]) ? $roomNumArray[$i] : null,
                'available_capacity' => $request->available_capacity[$i],
                'max_capacity' => $request->max_capacity[$i],
                'refundable' => $request->refundable[$i],
                'non_refundable' => $request->non_refundable[$i],
                'refundable_break' => $request->refundable_break[$i],
                'refundable_nonbreak' => $request->refundable_nonbreak[$i],
                'room_size' => $request->room_size[$i],
                'cancellation_policy' => $request->cancellation_policy[$i],
                'room_available' => $request->room_available[$i],
                'extra_bed' => $request->extra_bed[$i],
                'room_facilities' => is_array($request->room_facilities) ? implode(',', $request->room_facilities) : $request->room_facilities[$i],
                'bed_type' => is_array($request->bed_type) ? implode(',', $request->bed_type) : ($request->bed_type[$i] ?? null),

                // 'room_facilities' => is_array($request->room_facilities[$i]) ? implode(',', $request->room_facilities[$i]) : $request->room_facilities[$i],
                // 'bed_type' => is_array($request->bed_type[$i]) ? implode(',', $request->bed_type[$i]) : $request->bed_type[$i],
            ];
        }

        room::insert($roomDetails);

        return response()->json(['message' => 'Room Data successfully inserted'], 201);
    }
    public function  roomEdit($id)
    {
        self::$product = room::find($id);
        return view('admin.country.product-edit', [
            'product' => self::$product
        ]);
    }

    public static function roomUpdate(Request $request)
    {
        $product = room::find($request->id);

        if (!$product) {
            // Handle the case where the product is not found
            return response()->json(['error' => 'Product not found'], 404);
        }
        $product->room_num = $request->room_num;
        $product->available_capacity = $request->available_capacity;
        $product->max_capacity = $request->max_capacity;
        $product->refundable = $request->refundable;
        $product->non_refundable = $request->non_refundable;
        $product->refundable_break = $request->refundable_break;
        $product->refundable_nonbreak = $request->refundable_nonbreak;
        $product->room_size = $request->room_size;
        $product->cancellation_policy = $request->cancellation_policy;
        $product->room_available = $request->room_available;
        //$product->long_decription = $request->long_decription;
        $product->extra_bed = $request->extra_bed;
        $product->bed_type = $request->bed_type;
        $product->room_facilities = $request->room_facilities;

        $product->save();
        return response()->json(['message' => 'Room updated successfully'], 200);
    }

    public static function update(Request $request)
    {
        $product = room::find($request->id);

        if (!$request->has('room_num')) {
            return response()->json(['error' => 'Room number is required'], 400);
        }


        // Validate request data
        $request->validate([
            'room_num' => 'required',
            'available_capacity' => 'required',
            'max_capacity' => 'required',
            'refundable' => 'required',
            'non_refundable' => 'required',
            'refundable_break' => 'required',
            'refundable_nonbreak' => 'required',
            'room_size' => 'required',
            'cancellation_policy' => 'required',
            'room_available' => 'required',
            // Add validation rules for other fields
        ]);

        $product->room_num = $request->room_num;
        $product->available_capacity = $request->available_capacity;
        $product->max_capacity = $request->max_capacity;
        $product->refundable = $request->refundable;
        $product->non_refundable = $request->non_refundable;
        $product->refundable_break = $request->refundable_break;
        $product->refundable_nonbreak = $request->refundable_nonbreak;
        $product->room_size = $request->room_size;
        $product->cancellation_policy = $request->cancellation_policy;
        $product->room_available = $request->room_available;
        // $product->long_decription = $request->long_decription; // Commented out, as it is not present in your original code
        $product->extra_bed = $request->extra_bed;
        $product->bed_type = $request->bed_type;
        $product->room_facilities = $request->room_facilities;

        $product->save();

        return response()->json(['message' => 'Room updated successfully'], 200);
    }


    public function deleteroom(Request $request)
    {
        $roomId = $request->id;
        $room = room::find($roomId);

        if (!$room) {
            return response()->json(['message' => 'Room with ID ' . $roomId . ' not found'], 404);
        }
        room::deleteroom($roomId);

        return response()->json(['message' => 'Room deleted successfully'], 200);
    }

    public function detail($id)
    {
        $hotel = room::with('rooms')->find($id);

        return view('admin.product.detail', ['hotel' => $hotel]);
    }

    //passenger Info

    public function createPassenger(Request $request)
    {

        $address = Address::create([

            //'agency_info_id' => $request->agency_info_id,
            'AddressLine' => $request->AddressLine,
            'CityName' => $request->CityName,
            'CountryCode' => $request->CountryCode,
            'PostalCode' => $request->PostalCode,
            'StreetNmbr' => $request->StreetNmbr,
        ]);

        //'country_id' => $country->id,
        $country = StateCountyProv::create([
            'agency_info_id' => $address->id,
            'StateCode' => $request->StateCode,
        ]);


        //'country_id' => $country->id,
        $country = ContactNumbers::create([
            'customer_info_id' => $request->customer_info_id,
            'NameNumber' => $request->NameNumber,
            'Phone' => $request->Phone,
            'CountryCode' => $request->CountryCode,
            'PhoneUseType' => $request->PhoneUseType,
        ]);
        return response()->json(['status' => 'success', 'message' => 'Passenger record created successfully']);
    }

    public function managepassenger(Request $request)
    {
        $token = $request->header('Authorization');
        if (!empty($token)) {
            if ($this->verify_jwt_token($token)) {
                try {
                    // Set custom headers
                    $addresses = Address::with(['stateCountyProv'])->get();

                    // Hide the created_at and updated_at fields
                    $addresses->makeHidden(['created_at', 'updated_at']);

                    return response()->json($addresses);
                } catch (\Exception $ex) {
                    // Log or handle exceptions
                    return response()->json(['error' => 'An error occurred: ' . $ex->getMessage()], 500);
                }
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['error' => 'Authorization header is missing'], 401);
        }
    }

    // public function index()
    // {

    //   $response = Http::withHeaders([
    //             'Content-Type' => 'application/x-www-form-urlencoded',
    //             'Authorization' => 'Basic VmpFNk5UazBPREkyT2pnMFFrczZRVUU9OlFXRnRNbXcxTWpFPQ==',
    //             'Cookie' => "incap_ses_33_2768614=lgIwd7AZ7znYrsYPrj11APhyE2YAAAAAbwDPLebt9UHlN3VZpis+rg==; nlbi_2768614=caDuddWY3Wd/GnjiRh9LCAAAAAAy/0pCOxdJ+UzQvPnttEXY; visid_incap_2768614=3UihkdBxSSS/Cv1KOCgmD3ZeEmYAAAAAQUIPAAAAAAARN1gfYccSVZTW0qWsvMek",
    //         ])->post('https://api.cert.platform.sabre.com/v2/auth/token', [
    //             'grant_type' => 'client_credentials',
    //         ]);

    //         return $response->json();

    // }

    public function index()
    {
        try {
            $payload = http_build_query([
                'grant_type' => 'client_credentials',
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic VmpFNk5UazBPREkyT2pnMFFrczZRVUU9OlFXRnRNbXcxTWpFPQ==',
                'Cookie' => 'incap_ses_33_2768614=lgIwd7AZ7znYrsYPrj11APhyE2YAAAAAbwDPLebt9UHlN3VZpis+rg==; nlbi_2768614=caDuddWY3Wd/GnjiRh9LCAAAAAAy/0pCOxdJ+UzQvPnttEXY; visid_incap_2768614=3UihkdBxSSS/Cv1KOCgmD3ZeEmYAAAAAQUIPAAAAAAARN1gfYccSVZTW0qWsvMek',
            ])->post('https://api.cert.platform.sabre.com/v2/auth/token?' . $payload);

            return $response->json();
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    // public function gethotel(Request $request)
    // {
    //     $data = $request->all(); 

    //     $newData = $data['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'];

    //     $newHotel = array_map(function($item) {
    //         return array('name' => $item['HotelInfo']['HotelName'], 'location' => $item['HotelInfo']['LocationInfo']);
    //     }, $newData);

    //     return response()->json($newHotel);
    // }

    // public function apisearch(Request $request)
    // {
    //     try {
    //         // Prepare the request data
    //         $countryCode = $request->input('countryCode');
    //         $cityCode = $request->input('cityCode');

    //         $requestData = [
    //             'GetHotelAvailRQ' => [
    //                 'SearchCriteria' => [
    //                     'OffSet' => 1,
    //                     'SortBy' => 'TotalRate',
    //                     'SortOrder' => 'ASC',
    //                     'PageSize' => 20,
    //                     'TierLabels' => false,
    //                     'GeoSearch' => [
    //                         'GeoRef' => [
    //                             'Radius' => 20,
    //                             'UOM' => 'MI',
    //                             'RefPoint' => [
    //                                 'Value' => 'DFW',
    //                                 'ValueContext' => 'CODE',
    //                                 'RefPointType' => '6'
    //                             ]
    //                         ]
    //                     ],
    //                     'RateInfoRef' => [
    //                         'ConvertedRateInfoOnly' => false,
    //                         'CurrencyCode' => 'USD',
    //                         'BestOnly' => '2',
    //                         'PrepaidQualifier' => 'IncludePrepaid',
    //                         'StayDateRange' => [
    //                             'StartDate' => '2024-06-05',
    //                             'EndDate' => '2024-06-08'
    //                         ],
    //                         'Rooms' => [
    //                             'Room' => [
    //                                 [
    //                                     'Index' => 1,
    //                                     'Adults' => 1,
    //                                     'Children' => 0
    //                                 ]
    //                             ]
    //                         ],
    //                         'InfoSource' => '100,110,112,113'
    //                     ],
    //                     'HotelPref' => [
    //                         'SabreRating' => [
    //                             'Min' => '3',
    //                             'Max' => '5'
    //                         ]
    //                     ],
    //                     'ImageRef' => [
    //                         'Type' => 'MEDIUM',
    //                         'LanguageCode' => 'EN'
    //                     ]
    //                 ]
    //             ]
    //         ];

    //         $response1 = Http::withHeaders([
    //             'Content-Type' => 'application/json',
    //             'Accept' => 'application/json',
    //             'Conversation-ID' => '2021.01.DevStudio',
    //             'Authorization' => 'Bearer T1RLAQJURmHUsZcBVc6sfL5m6rvgICNgRFqSOnkGjGyjEJv6VhCAtMLmy3LcgDdehh7gRKJCAADQzQaV+9RXyzXg+q90UdaD8Gphs6ONRIlT2eCegn6k/1fRoVvigb/G4JwM/ULvec9Ih3EgTs5kvQnhQgE89J8HuLOWwQav9T2cVnz94+uv9qQyb28m6aGv0bH29UIn1jpEquHH2DMaoWHe91MNYR/UlrLK5OXPl8UroCUKaDaTB1enCq+ZoiZBd5ULogHCwjrT9P+qswN5hlZ7blL9QtTBbeKnn58RB2cPMwfT/OffOuZZFQiLenPMixOSLLD2UuKCkypH+6iM7y1tcA5lb48Mmw**'
    //         ])->post('https://api.platform.sabre.com/v3.0.0/get/hotelavail', $requestData);

    //         if ($response1->successful()) {
    //             // Extract data from the first response 
    //             $responseData1 = $response1->json()['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'];

    //             // Callback function to filter by country code
    //             function filterByCountryCode($hotel, $countryCode, $cityCode)
    //             {
    //                 // Check if the necessary nested values exist and if the country code or city code matches
    //                 return (
    //                     (isset($hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code']) &&
    //                         $hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code'] === $countryCode) ||
    //                     (isset($hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode']) &&
    //                         $hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode'] === $cityCode) ||
    //                     (isset($hotel['HotelInfo']['HotelName']) &&
    //                         $hotel['HotelInfo']['HotelName'] === $countryCode)
    //                 );
    //             }

    //             $filteredHotels = array_filter($responseData1, function ($hotel) use ($countryCode, $cityCode) {
    //                 return filterByCountryCode($hotel, $countryCode, $cityCode);
    //             });
    //             // Make the second Api GET request 
    //             $response2 = Http::timeout(5)->withHeaders([
    //                 'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2hvdGVsLmFvdHJlay5uZXQvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MjQwNTM5OTYsImV4cCI6MTcyNDA4OTk5NiwibmJmIjoxNzI0MDUzOTk2LCJqdGkiOiJjSjhrNTcwSldLeng0NWlrIiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.6-uisxhgg11bsxrQhlDPaYkxDmL3xC_0KXuKiTiUVuA',
    //             ])->get('https://hotel.aotrek.net/api/auth/manage-product');

    //             if ($response2->successful()) {
    //                 $responseData2 = $response2->json();

    //                 function filterByCountry($hotel, $countryCode, $cityCode)
    //                 {
    //                     if (isset($hotel['country']) && $hotel['country'] === $countryCode) {
    //                         return true;
    //                     }
    //                     if (isset($hotel['cities']) && is_array($hotel['cities'])) {
    //                         foreach ($hotel['cities'] as $city) {
    //                             if (isset($city['city']) && $city['city'] === $cityCode) {
    //                                 return true;
    //                             }
    //                         }
    //                     }

    //                     //  if (isset($hotel['hotels']) && is_array($hotel['hotels'])) {
    //                     //     foreach ($hotel['hotels'] as $hotel) {
    //                     //         if (isset($hotel['hotel']) && $hotel['hotel'] === $countryCode) {
    //                     //             return true;
    //                     //         }
    //                     //     }
    //                     // }

    //                     return false;
    //                 }
    //                 // Filter the array with the given country code
    //                 $filteredHotels1 = array_filter($responseData2, function ($hotel) use ($countryCode, $cityCode) {
    //                     return filterByCountry($hotel, $countryCode, $cityCode);
    //                 });

    //                 // Combine or manipulate data from both responses as needed
    //                 $combinedData = [
    //                     'filtered_hotels' => $filteredHotels,
    //                     'second_response_data' => $filteredHotels1,
    //                 ];

    //                 // Return the combined data in the JSON response
    //                 return response()->json($combinedData);
    //             } else {
    //                 return response()->json(['message' => 'Error fetching data from second external API'], $response2->status());
    //             }
    //         } else {
    //             return response()->json(['message' => 'Error fetching data from first external API'], $response1->status());
    //         }
    //     } catch (\Exception $error) {
    //         // Handle exceptions
    //         return response()->json(['message' => 'Error fetching data from external API'], 500);
    //     }
    // }
    
    
 public function apisearch(Request $request)
{
   try {
       
         // Prepare the request data
           $countryCode = $request->input('countryCode');
          $cityCode = $request->input('cityCode');
        // Prepare the headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Conversation-ID' => '2021.01.DevStudio',
            'Authorization' => 'Bearer T1RLAQKrKMUOeaJvQtROx99DICypr9d6CCZWdjjLhRdRYcE9WRD5s5dEI/rK2qwGIpZfNqWaAADQlXf47lm7vKL2Fv7g4WQuxYlc9Koo7Aeo52w9w9cBbQYQcklJ/whI3i7Ti3nVRuPdwdr5IszkzPxbr+P1W+ujaqwEq4rQDtXeMbx1nIz+hwnefuiQ5QYOy9eo3hqIFQCM3I7DAewoBRimhiJ0TUwn74No1y9GzNRxzprO/OfACoD+iydphwjWLGgVTIZT6/Lj7r5iGt/1bjIwHMlhonhIGoGNy/krR4pxjj8K3mEbqO8fB/x3bd58XE1lNIP3iIP6q7l5L7tReYzFHPX0/lhSpg**',
            'Cookie' => 'incap_ses_33_2768617=5EmMR1z2sRQ98hRLdj11AK4uw2YAAAAAi0swhtsv2zPjkEuHfz/Pqg==; visid_incap_2768617=zD9nbKVcQtOUaPXdT2lI1rvxwmYAAAAAQUIPAAAAAABBnsLiJd3s1EuSmsGJI5Y8',
        ];

        // Prepare the body
        $body = [
            'GetHotelAvailRQ' => [
                'SearchCriteria' => [
                    'OffSet' => 1,
                    'SortBy' => 'TotalRate',
                    'SortOrder' => 'ASC',
                    'PageSize' => 20,
                    'TierLabels' => false,
                    'GeoSearch' => [
                        'GeoRef' => [
                            'Radius' => 20,
                            'UOM' => 'MI',
                            'RefPoint' => [
                                'Value' => 'DFW',
                                'ValueContext' => 'CODE',
                                'RefPointType' => '6'
                            ]
                        ]
                    ],
                    'RateInfoRef' => [
                        'ConvertedRateInfoOnly' => false,
                        'CurrencyCode' => 'USD',
                        'BestOnly' => '2',
                        'PrepaidQualifier' => 'IncludePrepaid',
                        'StayDateRange' => [
                            'StartDate' => '2024-09-18',
                            'EndDate' => '2024-09-21'
                        ],
                        'Rooms' => [
                            'Room' => [
                                [
                                    'Index' => 1,
                                    'Adults' => 1,
                                    'Children' => 0
                                ]
                            ]
                        ],
                        'InfoSource' => '100,110,112,113'
                    ],
                    'HotelPref' => [
                        'SabreRating' => [
                            'Min' => '3',
                            'Max' => '5'
                        ]
                    ],
                    'ImageRef' => [
                        'Type' => 'MEDIUM',
                        'LanguageCode' => 'EN'
                    ]
                ]
            ]
        ];
 
        // Make the request
        $response1 = Http::withHeaders($headers)
                        ->post('https://api.platform.sabre.com/v3.0.0/get/hotelavail', $body);

        if ($response1->successful()) {
            // Extract data from the first response
            $responseData1 = $response1->json();

            // Validate response structure
            if (!isset($responseData1['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'])) {
                Log::error('Unexpected structure in first API response', ['response' => $responseData1]);
                return response()->json(['message' => 'Unexpected response structure from first external API'], 500);
            }

            $responseData1 = $responseData1['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'];

            $filteredHotels = array_filter($responseData1, function ($hotel) use ($countryCode, $cityCode) {
                $countryMatch = isset($hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code']) &&
                                $hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code'] === $countryCode;

                $cityMatch = isset($hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode']) &&
                             $hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode'] === $cityCode;

                return $countryMatch || $cityMatch;
            });

            // Second API request
            $response2 = Http::timeout(5)->withHeaders([
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2hvdGVsLmFvdHJlay5uZXQvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MjQwNjc4NDYsImV4cCI6MTcyNDEwMzg0NiwibmJmIjoxNzI0MDY3ODQ2LCJqdGkiOiJCMHZIb2U2a1RzdHFBZlJXIiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ._h8JUpqlyvLb0MOw3ypqoFdj5buhJKuTQadXx07t6iU'
            ])->get('https://hotel.aotrek.net/api/auth/manage-product');

            if ($response2->successful()) {
                $responseData2 = $response2->json();

                // Filter hotels from the second API
                $filteredHotels1 = array_filter($responseData2, function ($hotel) use ($countryCode, $cityCode) {
                    if (isset($hotel['country']) && $hotel['country'] === $countryCode) {
                        return true;
                    }
                    if (isset($hotel['cities']) && is_array($hotel['cities'])) {
                        foreach ($hotel['cities'] as $city) {
                            if (isset($city['city']) && $city['city'] === $cityCode) {
                                return true;
                            }
                        }
                    }
                    return false;
                });

                $response3 = Http::get('https://jsonplaceholder.typicode.com/posts');

                if ($response3->successful()) {
                    $responseData3 = $response3->json();

                    // Combine or manipulate data from all responses as needed
                    $combinedData = [
                        'filtered_hotels_first_api' => $filteredHotels,
                        'filtered_hotels_second_api' => $filteredHotels1,
                        'json_placeholder_data' => $responseData3
                    ];

                    return response()->json($combinedData);
                } else {
                    Log::error('JSONPlaceholder API request failed', [
                        'status' => $response3->status(),
                        'body' => $response3->body()
                    ]);
                    return response()->json(['message' => 'Error fetching data from JSONPlaceholder API'], $response3->status());
                }
            } else {
                Log::error('Second API request failed', [
                    'status' => $response2->status(),
                    'body' => $response2->body()
                ]);
                return response()->json(['message' => 'Error fetching data from second external API'], $response2->status());
            }
        } else {
            Log::error('First API request failed', [
                'status' => $response1->status(),
                'body' => $response1->body()
            ]);
            return response()->json(['message' => 'Error fetching data from first external API'], $response1->status());
        }
    } catch (\Exception $error) {
        Log::error('Exception occurred', ['message' => $error->getMessage(), 'trace' => $error->getTraceAsString()]);




    }
}

    public function newlogin(Request $request)
    {
        $email = $request->input('bsUsername');
        $password = $request->input('password');

        // URL of the API
        $url = "https://dev.travelbusinessportal.com/test/index";

        $payload = ["bsUsername" => $email, "password" => $password];

        // Send POST request to the API
        $response = Http::post($url, $payload);

        if ($response->successful()) {
            $apiResponse = $response->json();

            $authorizationToken = $apiResponse[' '] ?? null;
            return response()->json([
                'Authorization' => $authorizationToken
            ]);
        } else {
            // If request was not successful, return error response
            return response()->json(['error' => 'Unable to authenticate.'], $response->status());
        }
    }

    private function verify_jwt_token($jwt_token)
    {
        $jwt_token = str_replace('Bearer ', '', $jwt_token);
        $key = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; // Change this to your secret key
        try {
            $decoded = JWT::decode($jwt_token, $key, array('HS256'));
            return $decoded;
        } catch (\Exception $e) {
            echo "Token validation error: " . $e->getMessage();
            return false;
        }
    }

    public function gethotel()
    {
        try {
            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Conversation-ID' => '2021.01.DevStudio',
                'Authorization' => 'Bearer T1RLAQKPcYnCsL/hADNRvus0AyfDe2P5tUuhnrw1WrOQsuMrwxADAonGbFEcUWc2KKMAQxETAADQY2yEXFqs7EnmmWhknfxLH5HNqpOzjaiDmuGszpOnaUI8tuttiCbG3o5qA85HGIuOUQvm9d1egxcZZ+A9vD7nVbYOpk8GBnmAlNCegJPi5nNKQ6YHErxzwvSkyVVBssbFXE90qD8pKfyH322HFtbcXgrO8Qic2bjzHApIrSjRqqjmWja/bk/2T7UH8bGokVS2S1ggXibL9vlLGQShrFSFLbOf9637Zi4oOkxIU7QqkFbikTc5JJy4cYqsqessvAkEIj6Hr8bf9UemlbOycGrERg**',
                'Cookie' => 'incap_ses_713_2768614=HB5qRiMkaHvc9JmWvRXlCb3qFGYAAAAAw2syhArJX9SyOCS5n9mdgQ==; nlbi_2768614=se6lPcg2uDEQ9MyDRh9LCAAAAACGpWXI4fpPekxUQh1BAc5s; visid_incap_2768614=+GhiYSaOTSmVoOn4u75b7nlyxGUAAAAAQUIPAAAAAADL6sdk70vo1MSpy/FQaWE6',
            ];

            $body = [
                'GetHotelAvailRQ' => [
                    'SearchCriteria' => [
                        'OffSet' => 1,
                        'SortBy' => 'TotalRate',
                        'SortOrder' => 'ASC',
                        'PageSize' => 20,
                        'TierLabels' => false,
                        'GeoSearch' => [
                            'GeoRef' => [
                                'Radius' => 20,
                                'UOM' => 'MI',
                                'RefPoint' => [
                                    'Value' => 'DFW',
                                    'ValueContext' => 'CODE',
                                    'RefPointType' => '6'
                                ]
                            ]
                        ],
                        'RateInfoRef' => [
                            'ConvertedRateInfoOnly' => false,
                            'CurrencyCode' => 'USD',
                            'BestOnly' => '2',
                            'PrepaidQualifier' => 'IncludePrepaid',
                            'StayDateRange' => [
                                'StartDate' => '2024-05-09',
                                'EndDate' => '2024-05-12'
                            ],
                            'Rooms' => [
                                'Room' => [
                                    [
                                        'Index' => 1,
                                        'Adults' => 1,
                                        'Children' => 0
                                    ]
                                ]
                            ],
                            'InfoSource' => '100,110,112,113'
                        ],
                        'HotelPref' => [
                            'SabreRating' => [
                                'Min' => '3',
                                'Max' => '5'
                            ]
                        ],
                        'ImageRef' => [
                            'Type' => 'MEDIUM',
                            'LanguageCode' => 'EN'
                        ]
                    ]
                ]
            ];

            $response = Http::withHeaders($headers)->post('https://api.cert.platform.sabre.com/v3.0.0/get/hotelavail', $body);

            $response2 = Http::timeout(5)->withHeaders([
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2hvdGVsLmFvdHJlay5uZXQvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MTM0MjMzNTMsImV4cCI6MTcxMzQ1OTM1MywibmJmIjoxNzEzNDIzMzUzLCJqdGkiOiJKSlREZlFUQWNvTlRkY1hTIiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ._iDYu8XUOXNYoTOupPfF9wpb_nfAcqkYx-lmtfMyvKg',
            ])->get('https://hotel.aotrek.net/api/auth/manage-product');

            $data2 = $response2->json();
            $data = $response->json();

            // Return the combined data as a JSON response
            return response()->json(['data2' => $data2, 'data' => $data]);

            // $data = $response->json();

            // //return print_r($data);

            //return response()->json(['data' => $data]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }
    public function api()
    {
        $data = DB::table('users')->get();
        return response()->json($data);

        echo ('dxfvdcfg');
    }

    //  public function loginapi(Request $request)
    // { 
    //     // Assuming you have received email and password from the request
    //     $email = $request->input('bsUsername');
    //     $password = $request->input('password');

    //     // URL of the API
    //     $url = "http://dev.travelbusinessportal.com/test";

    //     // Payload for authentication    `
    //     $payload = ["bsUsername" => $email, "password" => $password];

    //     // Send POST request to the API
    //     $response = Http::post($url, $payload);

    //     // Check if request was successful
    //     if ($response->successful()) {
    //         // Parse JSON response
    //         $apiResponse = $response->json();

    //         // Extract Authorization token
    //         $authorizationToken = $apiResponse['Authorization'] ?? null;

    //         // Store the Authorization token in the session
    //         // session(['Authorization' => $authorizationToken]);

    //         // Return response with the token
    //         return response()->json(['Authorization' => $authorizationToken]);
    //     } else {
    //         // If request was not successful, return error response
    //         return response()->json(['error' => 'Unable to    .'], $response->status());
    //     }
    // }


    public function insure_db_order_create(Request $request)
    {
        $validatedData = $request->validate([
            'omc_id' => 'required|string|max:50',
            'name_as_passport' => 'required|string|max:255',
            'passport_number' => 'required|string|max:255',
            'permanent_address' => 'required|string|max:255', // Corrected field name
            'delivery_receiver_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'delivery_address' => 'required|string|max:255',
            'remark' => 'nullable|string',

            'country' => 'required|string',
            'trv_purpose' => 'required|string|max:255',
            'trv_dob' => 'required|string',
            'trv_dot' => 'required|string',
            'trv_duration' => 'required|string'
        ]);

        $id = DB::table('insurances')->insertGetId($validatedData);

        $insurance = DB::table('insurances')->where('omc_id', $id)->first();

        return response()->json($insurance, 201);
    }

    public function country(Request $request)
    {
        $token = $request->header('Authorization');
        if (!empty($token)) {
            if ($this->verify_jwt_token($token)) {
                $data = DB::table('activity_country')->get();
                return response()->json($data);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['error' => 'Authorization header is missing'], 401);
        }
    }
}
