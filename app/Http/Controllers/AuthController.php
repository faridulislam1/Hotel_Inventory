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
use App\Models\hotelbook;
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
use Illuminate\Support\Facades\Cache;

// require_once public_path('php-jwt/JWT.php');
// require_once public_path('php-jwt/BeforeValidException.php');
// require_once public_path('php-jwt/ExpiredExceptio n.php');
// require_once public_path('php-jwt/SignatureInvalidException.php');

 class AuthController extends Controller
{
    private static $hotel, $countries, $name, $product, $image, $imageName, $directory, $imageUrl, $otherImage, $imageExtension;

    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);
    
    //     // Retrieve the credentials from the request
    //     $credentials = $request->only('email', 'password');
    
    //     // Attempt to authenticate the user
    //     if (!Auth::attempt($credentials)) {
    //         return response()->json([
    //             'message' => 'Invalid email or password',
    //         ], 401);
    //     }
    //     // Get the authenticated user
    //     $user = Auth::user();
    //     $user->makeHidden(['created_at', 'updated_at']); 
    
    //     return response()->json([
    //         'user' => [
    //             'email' => $user->email, 
    //             'country' => $user->country,
    //             'division' => $user->division,
    //             'district' => $user->district, 
    //             'address' => $user->address,
    //             'contact_no' => $user->contact_no, // Add contact_no field
    //             'company_name' => $user->company_name, // Add company_name field
    //             'company_persons_name' => $user->company_persons_name, // Add company_persons_name field
    //             'currency' => $user->currency, // Add currency field
    //             'balance' => $user->balance, // Add balance field
    //             'credit_balance' => $user->credit_balance, // Add credit_balance field
    //             'status' => $user->status, // Add status field
    //             'token' => Auth::tokenById($user->id), // Token for the authenticated user
    //         ],
    //         'message' => 'Successfully logged in',
    //     ]);
    // }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $email = $request->email;
        $cacheKey = "login_attempts:{$email}";
        $blockedKey = "login_blocked:{$email}";

        if (Cache::has($blockedKey)) {
            return response()->json([
                'message' => 'Your account is temporarily blocked due to multiple failed login attempts. Please try again later.',
            ], 403);
        }
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            $attempts = Cache::increment($cacheKey);

            if ($attempts >= 3) {
            
                Cache::forever($blockedKey, true);
                Cache::forget($cacheKey);

                return response()->json([
                    'message' => 'Your account has been permanently blocked due to multiple failed login attempts. Please contact support.',
                ], 403);
            }

            // // Set initial attempts if not already set
            // if ($attempts === 1) {
            //     Cache::put($cacheKey, 1, now()->addMinutes(1));
            // }

            return response()->json([
                'message' => 'Invalid email or password',
                'remaining_attempts' => 3 - $attempts,
            ], 401);
        }

        // Successful login: clear any failed attempts cache
        Cache::forget($cacheKey);

        // Get the authenticated user
        $user = Auth::user();
        // $user->makeHidden(['created_at', 'updated_at']);

        return response()->json([
            'message' => 'success',
            'status' => 200,
            'data' => [
                'email' => $user->email,
                'country' => $user->country,
                'division' => $user->division,
                'district' => $user->district,
                'address' => $user->address,
                'contact_no' => $user->contact_no,
                'company_name' => $user->company_name,
                'company_persons_name' => $user->company_persons_name,
                'currency' => $user->currency,
                'balance' => $user->balance,
                'credit_balance' => $user->credit_balance,
                'status' => $user->status,
                'token' => Auth::tokenById($user->id),
            ],
        
        ]);
    }
    
    public function register(Request $request)
            {
                // Validate the request
                $request->validate([
                    'email' => 'required|string|email|max:255|unique:users,email,' . $request->email,
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
                    'email' => $request->email,
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
        $validator = Validator::make($request->all(), [
            'country' => 'required',
            'city' => 'required',
            'hotel' => 'required',
            'embed_code' => 'required',
            'landmark' => 'required',
            'rating' => 'required',
            'address' => 'required',
            'highlights' => 'required',
            'long_decription' => 'required',
            'litetitude' => 'required',
            'Single_image' => 'required',
            'multiple_image' => 'required',
            'facilities' => 'required',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
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

    // public function hotelshow(Request $request)
    // {
    //     try {
          
    //         $countries = Country::with(['cities.hotels.rooms'])->get();        
    //         $countries->each(function ($country) {        
    //             $country->makeHidden(['created_at', 'updated_at']);
    
    //             $country->cities->each(function ($city) {
    //                 $city->makeHidden(['created_at', 'updated_at', 'country_id']);
    
    //                 $city->hotels->each(function ($hotel) {
    //                     $hotel->makeHidden(['created_at', 'updated_at', 'country_id', 'city_id']);                      
    //                     if (!empty($hotel->multiple_image) && is_string($hotel->multiple_image)) {
    //                         $images = @unserialize($hotel->multiple_image);
    //                         $hotel->multiple_image = is_array($images) ? array_values($images) : [];
    //                     } else {
    //                         $hotel->multiple_image = [];
    //                     }                      
    //                     $hotel->facilities = is_array($hotel->facilities)
    //                         ? $hotel->facilities
    //                         : array_filter(explode(',', $hotel->facilities));
    
    //                     $hotel->rooms->each(function ($room) {
                          
    //                         $room->inventory = [
    //                             'totalRooms' => $room->total_rooms,
    //                             'allocatedOnlineInventory' => $room->allocated_online_inventory,
    //                             'allocatedOfflineInventory' => $room->allocated_offline_inventory,
    //                         ];
    //                         $room->updated_sales = $room->online_sold + $room->offline_sold;
    //                         $room->bed_type = is_array($room->bed_type)
    //                             ? $room->bed_type
    //                             : array_filter(explode(',', $room->bed_type));
    
    //                         $room->room_facilities = is_array($room->room_facilities)
    //                             ? $room->room_facilities
    //                             : array_filter(explode(',', $room->room_facilities));
    
                    
    //                         $room->makeHidden([
    //                             'created_at', 'updated_at', 'hotel_id', 'city_id',
    //                             'total_rooms', 'allocated_online_inventory', 'allocated_offline_inventory',
    //                             'online_sold', 'offline_sold',
    //                         ]);
    //                     });
    //                 });
    //             });
    //         });         
    //         return response()->json([
    //             'message' => 'Success',
    //             'status' => 200,
    //             'data' => $countries,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'An error occurred',
    //             'status' => 500,
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    
    


    public function hotelshow(Request $request)
    {
        try {
            $hotels = Hotel::with(['city.country', 'rooms'])->get();
    
            $formattedHotels = $hotels->map(function ($hotel) {
                return [
                    'id' => $hotel->id,
                    'hotel' => $hotel->hotel, 
                    'embed_code' => $hotel->embed_code,
                    'landmark' => $hotel->landmark,
                    'rating' => $hotel->rating,
                    'country' => $hotel->city->country->country ?? null, 
                    'city' => $hotel->city->city ?? null,
                    // 'Single_image' => $hotel->Single_image,
                    'images' => !empty($hotel->multiple_image) && is_string($hotel->multiple_image)
                        ? (is_array(@unserialize($hotel->multiple_image)) ? array_values(@unserialize($hotel->multiple_image)) : [])
                        : [],
                    'address' => $hotel->address,
                    'highlights' => $hotel->highlights,
                    'long_decription' => $hotel->long_decription,
                    'currency' => $hotel->currency,
                    'term_condition' => $hotel->term_condition,
                    'longitude' => $hotel->longitude,
                    'litetitude' => $hotel->litetitude,
    
                    'facilities' => is_array($hotel->facilities)
                    ? $hotel->facilities
                    : array_filter(explode(',', $hotel->facilities)),
                    
                    'rooms' => $hotel->rooms->map(function ($room) {
                        return [
                            'id' => $room->id,
                            'inventory' => [
                                'totalRooms' => $room->total_rooms,
                                'allocatedOnlineInventory' => $room->allocated_online_inventory,
                                'allocatedOfflineInventory' => $room->allocated_offline_inventory,
                            ],
                            'updated_sales' => $room->online_sold + $room->offline_sold,
                            'bed_type' => is_array($room->bed_type)
                                ? $room->bed_type
                                : array_filter(explode(',', $room->bed_type)),
                            'room_facilities' => is_array($room->room_facilities)
                                ? $room->room_facilities
                                : array_filter(explode(',', $room->room_facilities)),
                        ];
                    }),
                ];
            });
    
            return response()->json([
                'message' => 'Success',
                'status' => 200,
                'data' => $formattedHotels,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'status' => 500,
                'error' => $e->getMessage(),
            ], 500);
        }
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

    //booking
    public function booking()
    {
        $bookings = Room::select([
            'id as room_id',
            'hotel_id',
            'available_capacity',
            'max_capacity',
            'room_available'
        ])->get();
    
        return response()->json([
            'success' => true,
            'data' => $bookings,
            'message' => 'Room booking data retrieved successfully'
        ], 200);
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
            $products = room::all();
            $formattedProducts = $products->map(function ($room) {
                return [
                    'id' => $room->id,
                    'hotel_id' => $room->hotel_id,
                    'city_id' => $room->city_id,
                    'room_type' => $room->room_type,
                    'available_capacity' => $room->available_capacity,
                    'max_capacity' => $room->max_capacity,
                    'room_available' => $room->room_available,
                    'refundable' => $room->refundable,
                    'non_refundable' => $room->non_refundable,
                    'refundable_break' => $room->refundable_break,
                    'refundable_nonbreak' => $room->refundable_nonbreak,
                    'extra_bed' => $room->extra_bed,
                    'room_size' => $room->room_size,
                    'bed_type' => $room->bed_type,
                    'cancellation_policy' => $room->cancellation_policy,
                    'room_facilities' => $room->room_facilities,
                    'inventory' => [
                        'totalRooms' => $room->total_rooms,
                        'allocatedOnlineInventory' => $room->allocated_online_inventory,
                        'allocatedOfflineInventory' => $room->allocated_offline_inventory,
                    ],
                    'sales' => [
                        'onlineSold' => $room->online_sold,
                        'offlineSold' => $room->offline_sold,
                    ],
                ];
            });
    
            return response()->json($formattedProducts);
        } else {
            return response()->json(['error' => 'Authorization header is missing'], 401);
        }
    }
    
    public function saveroom(Request $request)
    {
        
        $extraBedValue = $request->input('extra_bed');
        if ($extraBedValue === null || $extraBedValue === '') {
            $extraBedValue = 'No';
        }

        $product = (object)[
            'id' => $request->room_id,
        ];


        $roomDetails = [];

        $roomNumArray = is_array($request->room_type) ? $request->room_type : [$request->room_type];
        $products = count($roomNumArray);

        for ($i = 0; $i < $products; $i++) {
            $roomDetails[] = [

                'hotel_id' => $request->hotel_id,
                'city_id' => $request->city_id,
                'room_type' => isset($roomNumArray[$i]) ? $roomNumArray[$i] : null,
                'available_capacity' => $request->available_capacity[$i],
                'max_capacity' => $request->max_capacity[$i],
                'refundable' => $request->refundable[$i],
                'non_refundable' => $request->non_refundable[$i],
                'refundable_break' => $request->refundable_break[$i],
                'refundable_nonbreak' => $request->refundable_nonbreak[$i],
                'room_size' => $request->room_size[$i],
                'room_available' => $request->room_available[$i],
                'total_rooms' => $request->total_rooms[$i],
                'allocated_online_inventory' => $request->allocated_online_inventory[$i],
                'allocated_offline_inventory' => $request->allocated_offline_inventory[$i],
                'online_sold' => $request->online_sold[$i],
                'offline_sold' => $request->offline_sold[$i],
                // 'extra_bed' => $request->extra_bed[$i],
                // 'room_facilities' => is_array($request->room_facilities) ? implode(',', $request->room_facilities) : $request->room_facilities[$i],
                // 'bed_type' => is_array($request->bed_type) ? implode(',', $request->bed_type) : ($request->bed_type[$i] ?? null),
                'cancellation_policy' => $request->cancellation_policy[$i],
                'cancellation_policy' => is_array($request->cancellation_policy) ? implode(',', $request->cancellation_policy) : $request->cancellation_policy, 
                'extra_bed' => is_array($request->extra_bed) ? implode(',', $request->extra_bed) : $request->extra_bed, 
                'room_facilities' => is_array($request->room_facilities) ? implode(',', $request->room_facilities) : $request->room_facilities, 
                'bed_type' => is_array($request->bed_type) ? implode(',', $request->bed_type) : $request->bed_type, 


            ];
        }

        room::insert($roomDetails);

        return response()->json(['message' => 'Room Data successfully inserted'], 201);
    }


    public function bookHotel(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'hotel_id' => 'required|integer|exists:hotels,id',
            'room_id' => 'required|integer|exists:rooms,id',
            'rooms_booked' => 'required|integer|min:1',
            'booking_type' => 'required|string|in:online,offline',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date',
            'payment_status' => 'required|string|in:paid,pending',
        ]);

        $room = Room::find($validated['room_id']);

        if ($validated['rooms_booked'] > $room->total_rooms) {
            return response()->json(['error' => 'Not enough rooms available'], 400);
        }

        $booking = HotelBook::create([
            'hotel_id' => $validated['hotel_id'],
            'room_id' => $validated['room_id'],
            'rooms_booked' => $validated['rooms_booked'],
            'booking_type' => $validated['booking_type'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'payment_status' => $validated['payment_status'],
        ]);

        // Update room inventory and sales
        try {
            $this->updateRoomInventory($room, $validated['rooms_booked'], $validated['booking_type']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json([
            'message' => 'Booking successful',
            'booking' => $booking,
            'updated_inventory' => $room->only(['total_rooms', 'allocated_online_inventory', 'allocated_offline_inventory']),
            'updated_sales' => $room->sales,
        ]);
    }


    public function show_hotelbook()
    {
        $bookings = HotelBook::all()->makeHidden(['created_at', 'updated_at', 'user_id']);
        $formattedBookings = $bookings->map(function ($booking) {
            return [
                'success' => true,
                'statusCode' => 200,
                'data' => $booking,
    
            ];
        });

        return response()->json($formattedBookings, 200);
    }



    private function updateRoomInventory($room, $roomsBooked, $bookingType)
    {
        $sales = $room->sales ?? ['onlineSold' => 0, 'offlineSold' => 0];

        if ($bookingType === 'online') {
            $room->allocated_online_inventory -= $roomsBooked;
            $sales['onlineSold'] += $roomsBooked;
        } elseif ($bookingType === 'offline') {
            $room->allocated_offline_inventory -= $roomsBooked;
            $sales['offlineSold'] += $roomsBooked;
        }
        $room->total_rooms -= $roomsBooked;
        if ($room->total_rooms < 0 || $room->allocated_online_inventory < 0 || $room->allocated_offline_inventory < 0) {
            throw new \Exception('Inventory mismatch: booking exceeds available inventory');
        }
        $room->sales = $sales;
        $room->save();
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
 
   
    public function apisearch(Request $request)
{
  
       try {
            $countryCode = $request->input('countryCode');
            $cityCode = $request->input('cityCode');
            $hotelName = $request->input('hotelName');
            $authToken = $request->header('Sabretoken');

        if (!$authToken) {
            return response()->json(['error' => 'Sabre token is missing'], 400);
        }
   
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Conversation-ID' => '2021.01.DevStudio',
             'Authorization' => $authToken,
            // 'Authorization' => 'Bearer T1RLAQJCRVCAoLz/WUcIi1/WNjzHb4nLo662De/+iWKFPGTzeBDoqlhvSWE9tfSQLWutj77BAADQ9UCbsdLbUAC4ZH9z3NM8h0pcHJR1jtIkQDJAOxvOgLXmPe/4VKxOVQ08lOCfxZTQ8tVjYMRCG3qINllleY1OdiO79Eiv8Vva8oEu2COY/7OG0wUTsC+UVOBi4FCh7ExH51UIhYA6TTvhbKXXQZPPce2T6gHi4+o3GMR7fhNPHpmtC+8LoL3yyoXAfHYQCueySxNyBYWOmJkqQIhw4CGq6en2mlef5SIt151fy5sotmHkcQ4Qpd6ulK7ERCkr+P9TOiy+PK/f3mD+LUxuxbNS+g**',
            'Cookie' => 'incap_ses_33_2768617=xztsd21vp3qqweQhiz11ABxLgmcAAAAA3HxbNsuz0caM8hM6OmNYmg==; visid_incap_2768617=jcx4fKvUSdGUx0ZiZYKnwycXSGcAAAAAQUIPAAAAAADjcRGikEBwkueWK2j/c46g',
        ];
    // var_dump($headers);
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
                                'RefPointType' => '6',
                            ],
                        ],
                    ],
                    'RateInfoRef' => [
                        'ConvertedRateInfoOnly' => false,
                        'CurrencyCode' => 'USD',
                        'BestOnly' => '2',
                        'PrepaidQualifier' => 'IncludePrepaid',
                        'StayDateRange' => [
                            'StartDate' => '2025-02-10',
                            'EndDate' => '2025-02-13',
                        ],
                        'Rooms' => [
                            'Room' => [
                                [
                                    'Index' => 1,
                                    'Adults' => 1,
                                    'Children' => 0,
                                ],
                            ],
                        ],
                        'InfoSource' => '100,110,112,113',
                    ],
                    'HotelPref' => [
                        'SabreRating' => [
                            'Min' => '3',
                            'Max' => '5',
                        ],
                    ],
                    'ImageRef' => [
                        'Type' => 'MEDIUM',
                        'LanguageCode' => 'EN',
                    ],
                ],
            ],
        ]; 
        
        $response = Http::withHeaders($headers)
        ->post('https://api.platform.sabre.com/v3.0.0/get/hotelavail', $body);

    if ($response->successful()) {
        // Extract data from the first response
        $responseData1 = $response->json();

        if (!isset($responseData1['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'])) {
            Log::error('Unexpected structure in first API response', ['response' => $responseData1]);
            return response()->json(['message' => 'Unexpected response structure from first external API'], 500);
        }

        $responseData1 = $responseData1['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'];
        
        $filteredHotels = array_filter($responseData1, function ($hotel) use ($countryCode, $cityCode, $hotelName) {
            $countryMatch = isset($hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code']) &&
                $hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code'] === $countryCode;

            $cityMatch = isset($hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode']) &&
                $hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode'] === $cityCode;

            $hotelNameMatch = isset($hotel['HotelInfo']['HotelName']) &&
                $hotel['HotelInfo']['HotelName'] === $hotelName;

            return $countryMatch || $cityMatch || $hotelNameMatch;
        });

        // Transform the filtered hotels
        $transformedHotels = array_map(function ($hotel) {
            // Access the first RateInfo element to get CurrencyCode
            $currencyCode = null;
            if (isset($hotel['HotelRateInfo']['RateInfos']['RateInfo'][0]['CurrencyCode'])) {
                $currencyCode = $hotel['HotelRateInfo']['RateInfos']['RateInfo'][0]['CurrencyCode'];
            }
        
            return [
                'HotelCode' => $hotel['HotelInfo']['HotelCode'] ?? null,
                'hotel' => $hotel['HotelInfo']['HotelName'] ?? null,
                'rating' => $hotel['HotelInfo']['SabreRating'] ?? null,
                // 'distance' => $hotel['HotelInfo']['Distance'] ?? null,
                'latitude' => $hotel['HotelInfo']['LocationInfo']['Latitude'] ?? null,
                'longitude' => $hotel['HotelInfo']['LocationInfo']['Longitude'] ?? null,
                'city' => $hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode'] ?? null,
                'country' => $hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code'] ?? null,
                'address' => $hotel['HotelInfo']['LocationInfo']['Address']['AddressLine1'] ?? null,
                'currency' => $currencyCode,
       

                'facilities' => is_array($hotel['HotelInfo']['Amenities']['Amenity'])
                    ? array_map(function ($amenity) {
                        return $amenity['Description'] ?? '';
                    }, $hotel['HotelInfo']['Amenities']['Amenity'])
                    : array_filter(explode(',', $hotel['HotelInfo']['Amenities']['Amenity'] ?? '')),


                // 'facilities' => [
                // array_map(function($amenity) {
                //     return $amenity['Description'] ?? '';  
                // }, $hotel['HotelInfo']['Amenities']['Amenity'] ?? []),

                'Rooms' => array_map(function ($room) {
                        return [
                            'room_type' => $room['RoomType'] ?? null,
                            'NonSmoking' => $room['NonSmoking'] ?? null,
                            'GuestRoomInfo' => $room['GuestRoomInfo'] ?? null,
                            // 'bed_type' => $room['BedTypes']['BedType'] ?? [],

                              'bed_type' => [
                                $room['RoomDescription']['Name'] ?? null,
                                $room['RoomDescription']['Text'] ?? null,
                            ],


                            'facilities' => array_map(function ($amenity) {
                            return $amenity['Description'] ?? '';  
                        }, $room['Amenities']['Amenity'] ?? []),  
                    ];
                                
                    }, $hotel['HotelRateInfo']['Rooms']['Room'] ?? []),
                
                        // 'Image' => [
                        //     'images' => $hotel['HotelImageInfo']['ImageItem']['Image']['Url'] ?? null,
                        //     'Type' => $hotel['HotelImageInfo']['ImageItem']['Image']['Type'] ?? null,
                        // ],
                        "images"=>[
                           $hotel['HotelImageInfo']['ImageItem']['Image']['Url'] ?? null,
                        ]
            
            
                        
               
            ];
        }, $filteredHotels);

        // Second API request
        $bearerToken = $request->header('Authorization');
        if (!$bearerToken) {
            return response()->json(['message' => 'The authorization token is required.'], 400);
        }

        $response2 = Http::timeout(5)->withHeaders([
            'Authorization' => $bearerToken,
        ])->get('https://hotel.aotrek.net/api/auth/show_hotel');

        if ($response2->successful()) {
            $responseData2 = $response2->json();

            // Filter second API response
            // $filteredHotels1 = array_filter($responseData2['data'], function ($hotel) use ($countryCode, $cityCode, $hotelName) {
            //     if (!empty($countryCode)) {
            //         if (isset($hotel['country']) && $hotel['country'] === $countryCode) {
            //             return true;
            //         }
            //         return false;
            //     }

            //     if (!empty($cityCode)) {
            //         if (isset($hotel['cities']) && is_array($hotel['cities'])) {
            //             foreach ($hotel['cities'] as $city) {
            //                 if (isset($city['city']) && $city['city'] === $cityCode) {
            //                     return true;
            //                 }
            //             }
            //         }
            //         return false;
            //     }

            //     if (!empty($hotelName)) {
            //         if (isset($hotel['cities']) && is_array($hotel['cities'])) {
            //             foreach ($hotel['cities'] as $city) {
            //                 if (isset($city['hotels']) && is_array($city['hotels'])) {
            //                     foreach ($city['hotels'] as $hotelInfo) {
            //                         if (isset($hotelInfo['hotel']) && $hotelInfo['hotel'] === $hotelName) {
            //                             return true;
            //                         }
            //                     }
            //                 }
            //             }
            //         }
            //         return false;
            //     }
            //     return false;
            // });

            // $filteredHotels1 = array_filter($responseData2['data'], function ($hotel) use ($countryCode, $cityCode, $hotelName) {
            //     // Filter by countryCode
            //     if (!empty($countryCode)) {
            //         if (!isset($hotel['country']) || $hotel['country'] !== $countryCode) {
            //             return false;
            //         }
            //     }
            
            //     // Filter by cityCode
            //     if (!empty($cityCode)) {
            //         if (!isset($hotel['city']) || $hotel['city'] !== $cityCode) {
            //             return false;
            //         }
            //     }
            
            //     // Filter by hotelName
            //     if (!empty($hotelName)) {
            //         if (!isset($hotel['hotel']) || $hotel['hotel'] !== $hotelName) {
            //             return false;
            //         }
            //     }
            //     return true;
            // });

            $filteredHotels1 = array_filter($responseData2['data'], function ($hotel) use ($countryCode, $cityCode, $hotelName) {
                // Convert inputs to arrays if they are not already arrays
                $countryCodes = is_array($countryCode) ? $countryCode : [$countryCode];
                $cityCodes = is_array($cityCode) ? $cityCode : [$cityCode];
                $hotelNames = is_array($hotelName) ? $hotelName : [$hotelName];
            
                // Check if hotel matches ANY condition (OR logic)
                $matchesCountry = isset($hotel['country']) && in_array($hotel['country'], $countryCodes);
                $matchesCity = isset($hotel['city']) && in_array($hotel['city'], $cityCodes);
                $matchesHotelName = isset($hotel['hotel']) && in_array($hotel['hotel'], $hotelNames);
            
                // Return true if ANY of the conditions match
                return $matchesCountry || $matchesCity || $matchesHotelName;
            });
            

            $combinedData = [
                'data' => array_merge( $filteredHotels1,$transformedHotels)
            ];
            
            return response()->json($combinedData);
        } else {
            Log::error('Second API request failed', [
                'status' => $response2->status(),
                'body' => $response2->body(),
            ]);
            return response()->json(['message' => 'Error fetching data from second external API'], $response2->status());
        }
    } else {
        Log::error('First API request failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        return response()->json(['message' => 'Error fetching data from first external API'], $response->status());
    }
} catch (\Exception $error) {
    Log::error('Exception occurred', [
        'message' => $error->getMessage(),
        'trace' => $error->getTraceAsString(),
    ]);
    return response()->json(['message' => 'Error fetching data from external API', 'error' => $error->getMessage()], 500);
}

}


}


    

