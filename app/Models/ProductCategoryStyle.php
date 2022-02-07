<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryStyle extends Model
{
    use HasFactory;
     
    protected $table = 'product_category_style';
    protected $fillable = [
        'product_id', 'category_id' ,'category_styles_id' , 'type'
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
    public function style1()
    {
        return $this->belongsTo('App\Models\CategoryStyle1');
    }

    /**
    * 
    */
    public function style2()
    {
        return $this->belongsTo('App\Models\CategoryStyle2');
    }

    
}
