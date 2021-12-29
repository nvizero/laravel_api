<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $fillable = [
        'order_id', 'user_id', 'user_name', 'price', 'num', 'text'
    ];
    /**
     * 多層次分類
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'order_id', 'id');
    }
}
