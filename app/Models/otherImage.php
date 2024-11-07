<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otherImage extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'multiple_image'

    ];

    private static $otherImage, $otherImages, $image, $imageName, $directory, $imageUrl, $imageExtension;

    private function getImageUrl($image)
    {
        $imageExtension = $image->getClientOriginalExtension();
        $imageName = rand(1, 500000) . '.' . $imageExtension; // e.g., 123.jpg
        $directory = 'upload/product-single-image/';
        $image->move($directory, $imageName);
        return $directory . $imageName;
    }
    
    private function newOtherImages($images)
    {
        $imageUrls = [];
        foreach ($images as $image) {
            $imageUrl = $this->getImageUrl($image);
            $imageUrls[] = $imageUrl;
            $otherImage = new OtherImage();
            $otherImage->multiple_image = $imageUrl;
            $otherImage->save();
        }
        return $imageUrls;
    }
    
    

}
