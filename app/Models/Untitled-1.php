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
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

 class AuthController extends Controller
{
    private static $hotel, $countries, $name, $product, $image, $imageName, $directory, $imageUrl, $otherImage, $imageExtension;

//     public function __construct()
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

// public function registers(Request $request)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|string|email|max:255|unique:users',
//         'password' => 'required|string|min:6',
      
//     ]);

//     $user = User::create([
//         'name' => $request->name,
//         'email' => $request->email,
//         'password' => bcrypt($request->password),
     
//     ]);

//     return response()->json([
//         'message' => 'User created successfully',
//         'user' => $user
//     ]);
// }


  public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


// public function login(Request $request)
// {
//     try {
//         // Validate the input
//         $request->validate([
//             'email' => 'required|string|email',
//             'password' => 'required|string',
//         ]);

//         // Authenticate user
//         $credentials = $request->only('email', 'password');
//         if (!$token = auth()->attempt($credentials)) {
//             return response()->json([
//                 'status' => 401,
//                 'message' => 'Invalid email or password',
//             ], 401);
//         }

//         $user = auth()->user();

//         return response()->json([
//             'status' => 200,
//             'message' => 'Successfully logged in',
//             'data' => [
//                 'email' => $user->email,
//                 'country' => $user->country,
//                 'division' => $user->division,
//                 'district' => $user->district,
//                 'address' => $user->address,
//                 'contact_no' => $user->contact_no,
//                 'company_name' => $user->company_name,
//                 'company_persons_name' => $user->company_persons_name,
//                 'currency' => $user->currency,
//                 'balance' => $user->balance,
//                 'credit_balance' => $user->credit_balance,
//                 'status' => $user->status,
//                 'token' => $token,
//             ],
//         ], 200);

//     } catch (\Exception $e) {
//         \Log::error('Login error:', ['error' => $e->getMessage()]);

//         return response()->json([
//             'status' => 500,
//             'message' => 'Something went wrong. Please contact support.',
//         ], 500);
//     }
// }


public function login(Request $request)
{
    try {
        // Validate the input
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Authenticate user
        $credentials = $request->only('email', 'password');
        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user = auth()->user();

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
                'token' => $token,
            ],
        ], 200);

    } catch (TokenExpiredException $e) {
        return response()->json([
            'status' => 401,
            'message' => 'Token has expired. Please log in again.',
        ], 401);

    } catch (TokenInvalidException $e) {
        return response()->json([
            'status' => 401,
            'message' => 'Invalid token. Please log in again.',
        ], 401);

    } catch (JWTException $e) {
        return response()->json([
            'status' => 401,
            'message' => 'Token not provided. Please log in again.',
        ], 401);

    } catch (\Exception $e) {
        \Log::error('Login error:', ['error' => $e->getMessage()]);
        return response()->json([
            'status' => 500,
            'message' => 'Something went wrong. Please contact support.',
        ], 500);
    }
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
    // public function register(Request $request) 
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     return response()->json([
    //         'message' => 'User created successfully',
    //         //'user' => $user
    //     ]);
    // }

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
            // if ($this->verify_jwt_token($token)) {
            //     try {
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


            //     // } catch (\Exception $ex) {
            //     //     // Log or handle exceptions
            //     //     return response()->json(['error' => 'An error occurred: ' . $ex->getMessage()], 500);
            //     // }
            // } else {
            //     return response()->json(['error' => 'Unauthorized'], 401);
            // }
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
            // if ($this->verify_jwt_token($token)) {
            //     try {
                    // Set custom headers
                    $addresses = Address::with(['stateCountyProv'])->get();

                    // Hide the created_at and updated_at fields
                    $addresses->makeHidden(['created_at', 'updated_at']);

                    return response()->json($addresses);
            //     } catch (\Exception $ex) {
            //         // Log or handle exceptions
            //         return response()->json(['error' => 'An error occurred: ' . $ex->getMessage()], 500);
            //     }
            // } else {
            //     return response()->json(['error' => 'Unauthorized'], 401);
            // }
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
        $hotelCode = $request->input('hotelCode');
        // Prepare the headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Conversation-ID' => '2021.01.DevStudio',
            'Authorization' => 'Bearer T1RLAQLKRENn3MFIrNTbXWc0ExZ+yg9JmSvCVZmIBp0dqxJzcxCWHSxNCMDs9RzxPmKSVxmhAADQv893mq78Qwrgs6BYhgVVaYgALrc9NqdliXnVZ3l5Jo8k3UJipFiR4Fp/ppSURVVPzh3JtJCjiMuUhcNTPkpXtZMrdabiDLxrYg3QRdi22u5re5KJQTkxi3V7sf7s8iwGzH6WxjPMpJ04bLMI7gCBtOdncEk4r/P3FMbuaP9Kz+HZbdr3qTD2PoggyJyTdwLMs055uRYJIMuhg4KBp8+neNNtbFMNwCcAiz3UGEXH09v7OnJIc36jqaMY4Tpuw8wANHu9dsW3VxDl16u6hO+iDQ**',
            'Cookie' => 'incap_ses_1802_2768617=KsQnbSGon0eTtcQooP0BGf8bSGcAAAAA9ceE97NCAAxOaKimVEb9Yg==; visid_incap_2768617=jcx4fKvUSdGUx0ZiZYKnwycXSGcAAAAAQUIPAAAAAADjcRGikEBwkueWK2j/c46g',
        ];

        // Define the body
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
                            'StartDate' => '2024-12-28',
                            'EndDate' => '2024-12-31',
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

            // Validate response structure
            if (!isset($responseData1['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'])) {
                Log::error('Unexpected structure in first API response', ['response' => $responseData1]);
                return response()->json(['message' => 'Unexpected response structure from first external API'], 500);
            }

            $responseData1 = $responseData1['GetHotelAvailRS']['HotelAvailInfos']['HotelAvailInfo'];

            // Filter hotels by country code and city code
            $filteredHotels = array_filter($responseData1, function ($hotel) use ($countryCode, $cityCode,$hotelCode) {
                $countryMatch = isset($hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code']) &&
                 $hotel['HotelInfo']['LocationInfo']['Address']['CountryName']['Code'] === $countryCode;

                $cityMatch = isset($hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode']) &&
                $hotel['HotelInfo']['LocationInfo']['Address']['CityName']['CityCode'] === $cityCode;

                 $hotelMatch = isset($hotel['HotelInfo']['HotelName']) &&
                 $hotel['HotelInfo']['HotelName'] === $hotelCode;

                return $countryMatch || $cityMatch || $hotelMatch;
            });

            $bearerToken = $request->header('Authorization');
            if (!$bearerToken) {
                return response()->json([
                    'message' => 'The authorization token is required.'
                ], 400);
            }
        
            $response2 = Http::timeout(5)->withHeaders([
                'Authorization' => $bearerToken,
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

                // Combine or manipulate data from both responses as needed
                $combinedData = [
                    'filtered_hotels_first_api' => $filteredHotels1,
                    'filtered_hotels_second_api' => $filteredHotels,
                ];

                // Return the combined data in the JSON response
                return response()->json($combinedData);
            } else {
                Log::error('Second API request failed', [
                    'status' => $response2->status(),
                    'body' => $response2->body()
                ]);
                return response()->json(['message' => 'Error fetching data from second external API'], $response2->status());
            }
        } else {
            Log::error('First API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return response()->json(['message' => 'Error fetching data from first external API'], $response->status());
        }
    } catch (\Exception $error) {
        // Log the exception
        Log::error('Exception occurred', ['message' => $error->getMessage(), 'trace' => $error->getTraceAsString()]);
        return response()->json(['message' => 'Error fetching data from external API', 'error' => $error->getMessage()], 500);
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
                'Authorization' => 'Bearer T1RLAQJ8C0AOrX7N2B9AaKe7AemYtjkU0oEKrIFaTlBcyGSsjhCVsfk3vwobsqAjTaBMaOrjAADQRxYynfXiFvHOYdEdE0ggMSH44OnlCNWsTEeSI+l8d94zT12r10Fswy7ZtvI3D5XNNfsiQPB/viW7SQoRNsp/t9FDSIDd6GA0992R1o65uzBvUgr9zVXf6vhXQiyWs04IoeynlQy7dBUxAksf1AGB9X57Jid+LDABco9fP5LDvemu4nPSBc9BCABelsCcfRLGFQ0HjHLs8e11ycY5OsN4x2Ejw0DHvdUHXnPBp9yvj54i6sLTpPYIa/ZJ3Yp3iL8adFaHzvZJO59wnQnCqQsepA**',
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
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2hvdGVsLmFvdHJlay5uZXQvYXBpL2F1dGgvbG9naW4iLCJpYXQiOjE3MzI3NzgzNzMsImV4cCI6MTczMjgxNDM3MywibmJmIjoxNzMyNzc4MzczLCJqdGkiOiJGT1pxMGxTbklaN0x4b1F2Iiwic3ViIjoiMiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.x5jh6rOVwekYgyymanLfZR85sprp4hM7i9J_T8uYQS0',
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
