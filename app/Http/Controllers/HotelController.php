<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\City;
use App\Models\room;
use App\Models\hotel;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
        
        private static $hotel,$countries, $name,$product,$image,$imageName,$directory,$imageUrl,$otherImage,$imageExtension;
        public function storehotel(){
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
        // }
        public function manageProduct()
{
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

    return back()->with('message', 'Info saved');
}


  public function productEdit($id){
    self::$product=hotel::find($id);
    return view('admin.product.product-edit',[
        'product'=>self::$product
    ]);
}


public static function  Update(Request $request)
{
    $product = hotel::find($request->id);

    if (!$product) {
        // Handle the case where the product is not found
        return response()->json(['error' => 'Product not found'], 404);
    }

    $product->hotel = $request->hotel;
    $product->embed_code = $request->embed_code;
    $product->landmark = $request->landmark;
    $product->address = $request->address;
    $product->highlights = $request->highlights;
    $product->long_decription = $request->long_decription;
    $product->term_condition = $request->term_condition;
    $product->longitude = $request->longitude;
    $product->litetitude = $request->litetitude;
    $product->facilities = $request->facilities;

    if ($request->file('Single_image')) {
        // Delete the existing image file
        if ($product->Single_image && file_exists($product->Single_image)) {
            unlink($product->Single_image);
        }

        // Update Single_image with the new file
        $product->Single_image = self::getImageUrl($request, 'Single_image');
    }

    $product->save();


    return response()->json(['message' => 'Hotel updated successfully'], 200);
} 

    public function detail($id)
    {
        $hotel = Hotel::with('rooms')->find($id);
    
        return view('admin.product.detail', ['hotel' => $hotel]);
    }
             
    public function productDelete(Request $request){
        hotel::productDelete($request->id);
        return back()->with('message', 'Info deleted');

    }
 
    }
