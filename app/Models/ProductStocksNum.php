<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStocksNum extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_stock_id', 'num'
    ];    

    /**
     * Get the user that owns the phone.
     */
    public function style()
    {        
        return $this->belongsTo('App\Models\ProductStocks');
    }
     
}
