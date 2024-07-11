<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\itenary;
use App\Models\Hotel;
use App\Models\Country;
use App\Models\City;
use App\Models\Rooms;
class ItenaryController extends Controller
{
    
    public static  $products;
    private static $hotel, $product,$image,$imageName,$directory,$imageUrl,$otherImage,$imageExtension;

    
    public function storehotel(){
     
        $countries = Country::all();
        $cities = City::all();
        //$cities = City::with('country')->get();
        $rooms = Rooms::with('city.country')->get();
        return view('admin.country.add-product', compact('countries', 'cities', 'rooms'));
    }

    public function getSubCategoryByCategory()
    {

        return response()->json(City::where('country_id', $_GET['id'])->get());
    }

    public function hotel()
    {

        return response()->json(Rooms::where('city_id', $_GET['id'])->get());
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


    public function manageProduct(){
        
       //$products=itenary::with('city')->get();
         $products=itenary::all();
        return response()->json($products);
        //exit();
        return view('admin.country.manage-product',[
            'products'=>itenary::all()
        ]);
        // return view('admin.product.manage', ['products' => Product::all()]);
        //dd($products);
    }

   public function savehotel(Request $request){
 // dd($request);
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
            // 'hotel_id' => $request->hotel_id,
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
            'room_facilities' => is_array($request->room_facilities) ? implode(',', $request->room_facilities) : $request->room_facilities,
            'bed_type' => is_array($request->bed_type) ? implode(',', $request->bed_type) : $request->bed_type,
       
            // 'room_facilities' => is_array($request->room_facilities[$i]) ? implode(',', $request->room_facilities[$i]) : $request->room_facilities[$i],
            // 'bed_type' => is_array($request->bed_type[$i]) ? implode(',', $request->bed_type[$i]) : $request->bed_type[$i],
        ];
    }

    // Insert $roomDetails into the database using your preferred method
    // For example, using Eloquent's insert method to perform a batch insert
    itenary::insert($roomDetails);

    return back();
}

public function detail($id)
{
    $hotel = itenary::with('rooms')->find($id);

    return view('admin.product.detail', ['hotel' => $hotel]);
}

        
}
