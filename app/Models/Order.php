<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'title', 'user_id'
    ];
    /**
     * 多層次分類
     */
    public function childs()
    {
        return $this->hasMany('App\Models\OrderDetail', 'parent_id', 'id');
    }
}
