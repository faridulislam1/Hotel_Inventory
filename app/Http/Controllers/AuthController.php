<?php

namespace App\Http\Controllers;
use App\Models\User;
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

require_once public_path('php-jwt/JWT.php');
require_once public_path('php-jwt/BeforeValidException.php');
require_once public_path('php-jwt/ExpiredException.php');
require_once public_path('php-jwt/SignatureInvalidException.php');
 class AuthController extends Controller
{
    private static $hotel, $countries, $name, $product, $image, $imageName, $directory, $imageUrl, $otherImage, $imageExtension;

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
        $token = $request->header('Authorization');
        if (!empty($token)) {
            if ($this->verify_jwt_token($token)) {
                try {
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
    public static function getMultiImageUrl($request)
    {
        if ($request->hasFile('multiple_image')) {
            $images = $request->file('multiple_image');
            $imageUrls = [];

            foreach ($images as $image) {
                if ($image->isValid()) {
                    $imageExtension = $image->getClientOriginalExtension();
                    $imageName = rand(1, 500000) . '.' . $imageExtension;
                    $directory = 'upload/product-other-images/';
                    $image->move($directory, $imageName);

                    $imageUrl = $directory . $imageName;
                    $imageUrls[] = $imageUrl;
                } else {
                }
            }
            return $imageUrls;
        } else {
            return [];
        }
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
        $multipleImageUrls = self::getMultiImageUrl($request);

        hotel::create([
            'hotel' => $request->hotel,
            'city_id' => $city->id,
            // 'country_id' => $country->id,
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
            'Single_image' => $singleImageUrl,
            'multiple_image' => $multipleImageUrls !== null && is_array($multipleImageUrls) ? serialize($multipleImageUrls) : null,
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
    //   public function __construct()
    //     {
    //         $this->middleware('auth:api', ['except' => ['login', 'register']]);
    //     }

    // public function login(Request $request)


    // {

    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => '  |string',
    //     ]);
    //     $credentials = $request->only('email', 'password');
    //     $token = Auth::attempt($credentials);

    //     if (!Auth::attempt($credentials)) {
    //         return response()->json([
    //             'message' => 'Invalid email or password',
    //         ], 401);
    //     }
    //     $user = Auth::user();
    //      $user->makeHidden(['created_at', 'updated_at']);

    //     return response()->json([
    //         'user' => [

    //             'email' => $user->email,
    //             'token' => $token,
    //         ],
    //         'messege' => 'Successfully Login',
    //     ]);

    // }

    public function register(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            //'user' => $user
        ]);
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

    public function handle(Request $request, Closure $next)
    {
        return $next($request);

        $restrictedIps = ['127.0.0.1', '102.129.158.0'];
        if (in_array($request->ip(), $restrictedIps)) {
            App::abort(403, 'Request forbidden');
        }
        return $next($request);
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

    public function apisearch(Request $request)
    {
        try {
            // Prepare the request data
            $countryCode = $request->input('countryCode');
            $cityCode = $request->input('cityCode');

            $requestData = [
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
                                'StartDate' => '2024-06-05',
                                'EndDate' => '2024-06-08'
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

            $response1 = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Conversation-ID' => '2021.01.DevStudio',
                'Authorization' => 'Bearer T1RLAQLmEMoHOYBgpmbYgnejyIwPuNTSwcYh7fNPhtPk6+ZAVxA+FTGydgp6+HYSLhf3/ggYAADQP/bB6p4d0XrwXT25DB49bymNY5N8+Mibv+z2MBe3F1CP+9sOaqZgwUII4nGbYErTLHEPfBf0BEKVYdznHQl0tEdEWE7qZfu2Y+/dzFJKM3KtqbkBuid/vkfox6lDcOYuI9wWOsZhOtnEEEJQW68lJm+NJXDiGUK+OMTfIdMD3dMFMzb+q2Y64EzVg37e+2iW6a/8Br9f/eaWqY5rf814quAautxFHKlmkoLxcgOFW9CoQ8XRKCzie4lwD31vowex8+smJYKAdkribpvJnpugBA**'
            ])->post('https://api.cert.platform.sabre.com/v3.0.0/get/hotelavail', $requestData);

            if ($response1->successful()) {
                // Extract data from the first response 
                $responseData1 = $response1->json()['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'];

                // Callback function to filter by country code
                function filterByCountryCode($hotel, $countryCode, $cityCode)
                {
                    // Check if the necessary nested values exist and if the country code or city code matches
                    return (
                        (isset($hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code']) &&
                            $hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code'] === $countryCode) ||
                        (isset($hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode']) &&
                            $hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode'] === $cityCode) ||
                        (isset($hotel['HotelInfo']['HotelName']) &&
                            $hotel['HotelInfo']['HotelName'] === $countryCode)
                    );
                }

                $filteredHotels = array_filter($responseData1, function ($hotel) use ($countryCode, $cityCode) {
                    return filterByCountryCode($hotel, $countryCode, $cityCode);
                });
                // Make the second Api GET request 
                $response2 = Http::timeout(5)->withHeaders([
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MTUwNTgzNjUsImV4cCI6MTcxNTA5NDM2NiwibmJmIjoxNzE1MDU4MzY2LCJqdGkiOiJZd3dnVXk1TVRwRzgwelR5Iiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.KiZV777r3S591z9bPoDvYc2WI4P5IvxJ_avXATEbQGE',
                ])->get('http://127.0.0.1:8000/api/auth/manage-product');

                if ($response2->successful()) {
                    $responseData2 = $response2->json();

                    function filterByCountry($hotel, $countryCode, $cityCode)
                    {
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

                        //  if (isset($hotel['hotels']) && is_array($hotel['hotels'])) {
                        //     foreach ($hotel['hotels'] as $hotel) {
                        //         if (isset($hotel['hotel']) && $hotel['hotel'] === $countryCode) {
                        //             return true;
                        //         }
                        //     }
                        // }

                        return false;
                    }
                    // Filter the array with the given country code
                    $filteredHotels1 = array_filter($responseData2, function ($hotel) use ($countryCode, $cityCode) {
                        return filterByCountry($hotel, $countryCode, $cityCode);
                    });

                    // Combine or manipulate data from both responses as needed
                    $combinedData = [
                        'filtered_hotels' => $filteredHotels,
                        'second_response_data' => $filteredHotels1,
                    ];

                    // Return the combined data in the JSON response
                    return response()->json($combinedData);
                } else {
                    return response()->json(['message' => 'Error fetching data from second external API'], $response2->status());
                }
            } else {
                return response()->json(['message' => 'Error fetching data from first external API'], $response1->status());
            }
        } catch (\Exception $error) {
            // Handle exceptions
            return response()->json(['message' => 'Error fetching data from external API'], 500);
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
