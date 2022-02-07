<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'category_id' ,'style1','style2','num','price','image','content'
    ];    

    /**
    * 
    */
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * 
     */
    public function categoryStyle()
    {
        return $this->hasMany('App\Models\ProductCategoryStyle');
    }

    
}
