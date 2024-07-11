<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\Rooms;

class LocationController extends Controller
{
    //

    // public function index()
    // {
    //     $countries = Country::all();
    //     $cities = City::all();
    //     $rooms = Rooms::all();

    //     return view('locations.index', compact('countries', 'cities', 'rooms'));
    // }

    
    public function storehotel(){
        $countries = Country::all();
        $cities = City::all();
        $rooms = Rooms::all();
        return view('admin.product.add-product');
    }

    public function manageProduct(){
        
       // $products=Hotel::with('rooms')->get();
        $products=Hotel::all();
        return response()->json($products);
        //exit();
        return view('admin.product.manage-product',[
            'products'=>Hotel::all()
        ]);
        // return view('admin.product.manage', ['products' => Product::all()]);
        //dd($products);
    }

    

   public function savehotel(Request $request){
 // dd($request);
    $products = Hotel::all();

    $product = new Hotel();
    $product->itinerary = $request->itinerary;
    $product->itinerary_details = $request->itinerary_details;
    $product->save();


    return back()->with('message', 'Info saved');
}

}
