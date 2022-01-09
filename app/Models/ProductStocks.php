<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStocks extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'category_style_id'
    ];    

    /**
    * 
    */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
    * 產品型號/尺寸
    */
    public function style()
    {
        return $this->belongsTo('App\Models\CategoryStyle');
    }
}
