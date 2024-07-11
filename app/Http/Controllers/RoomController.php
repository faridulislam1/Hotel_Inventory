<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\room;
use App\Models\hotel;
use App\Models\Country;
use App\Models\City;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
  
    public static  $products;
    private static $hotel, $product,$image,$imageName,$directory,$imageUrl,$otherImage,$imageExtension;

    
    public function storeroom(){
     
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

    // public function getcity(Request $request)
    // {
    //     $countryId = $request->input('country_id');

    //     // Retrieve cities based on the selected country
    //     $cities = City::where('country_id', $countryId)->get();
    //   // dd($request);
    //     // Return the cities as JSON
    //     return response()->json($cities);
    // }

    // public function gethotel(Request $request)
    // {
    //     $cityId = $request->input('city_id');

    //     // Retrieve hotels based on the selected city
    //     $hotels = Rooms::where('city_id', $cityId)->get();

    //     // Return the hotels as JSON
    //     return response()->json($hotels);
    // }


    public function manageroom(){
        
       //$products=itenary::with('city')->get();
         $products=room::all();
       //return response()->json($products);
        //exit();
        return view('admin.country.manage-product',[
            'products'=>room::all()
        ]);
        // return view('admin.product.manage', ['products' => Product::all()]);
        //dd($products);
    }

   public function saveroom(Request $request){
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

    return back();
}
public function  roomEdit($id){
    self::$product=room::find($id);
    return view('admin.country.product-edit',[
        'product'=>self::$product
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
            
        public function destroy(Request $request)
        {
            room::destroy($request->id);
            return back()->with('message', 'Info deleted');
        }

public function detail($id)
{
    $hotel = room::with('rooms')->find($id);

    return view('admin.product.detail', ['hotel' => $hotel]);
}

}
