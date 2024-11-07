<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotel extends Model
{
    use HasFactory;
    private static $product;


    protected $fillable = [
        'hotel',
        'city_id',
        'country_id',
        'embed_code',
        'landmark',
        'rating',
        'Single_image',
        'address',
        'highlights',
        'long_decription',
        'currency',
        'term_condition',
        'longitude',
        'litetitude',
        'facilities'

    ];

    // public static function productDelete($id){
    //     self::$product=hotel::find($id);
    //     if(self::$product->Single_image){
    //         if(file_exists(self::$product->Single_image)){
    //             unlink(self::$product->Single_image);
    //             self::$product->delete();
    //         }
    //     }
    //     else{
    //         self::$product->delete();
    //     }
    // }
    

    public static function productDelete($id) {
        self::$product = hotel::find($id);
    
        if (self::$product) {
            // Delete related records in the rooms table
            self::$product->rooms()->delete();
    
            // Now you can safely delete the hotel record
            if (self::$product->Single_image) {
                if (file_exists(self::$product->Single_image)) {
                    unlink(self::$product->Single_image);
                }
            }
    
            self::$product->delete();
        }
    }
   
//    public function hotel(){
//     return $this->belongsTo(Hotel::class);
//    }

//    public function city()
//     {
//         return $this->belongsTo(City::class);
//     }

//     // public function itenary(){
//     //     return $this->belongsTo(itenary::class);
//     //    }

//        public function itineraries() {
//         return $this->hasMany(room::class);
//     }

//     public function rooms()
//     {
//         return $this->hasMany(room::class);
//     }
    // public function rooms(){
    //     return $this->hasMany(Room::class);
    //    }


    public function rooms()
    {
        return $this->hasMany(room::class);
    }

}






