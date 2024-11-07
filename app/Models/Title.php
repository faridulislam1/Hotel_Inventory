<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    Protected $fillable =['name','title','description'];
    private static $category,$image,$imageNewName,$directory,$imgUrl;

    public static function newCategory($request){
        self::$category = new Title();
        self::$category->name = $request->name;
        self::$category->title = $request->title;
        self::$category->description = $request->description;
        self::$category->save();

    }
    public static function destroy($id){
        self::$category=Title::find($id);
        self::$category->delete();
    }

    public static  function updates($request){
        self::$category=Title::find($request->id);
        self::$category->name = $request->name;
        self::$category->title = $request->title;
        self::$category->description = $request->description;
        self::$category->save();
    }
     
}
