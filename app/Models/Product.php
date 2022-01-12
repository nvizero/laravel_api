<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'price', 'description', 'image','txt','attrib','other_price'
    ];

    public function setImageAttribute($image)
    {
        if (is_array($image)) {
            $this->attributes['image'] = json_encode($image);
        }
    }

    public function getImageAttribute($image)
    {
        return json_decode($image, true);
    }

    /**
    * 多層次分類
    */
    public function stocks()
    {
        return $this->hasMany('App\Models\ProductStocks');
    }

    public function getAttribAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setAttribAttribute($value)
    {
        $this->attributes['attrib'] = json_encode(array_values($value));
    }

    public function getOtherPriceAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setOtherPriceAttribute($value)
    {
        $this->attributes['other_price'] = json_encode(array_values($value));
    }
}
