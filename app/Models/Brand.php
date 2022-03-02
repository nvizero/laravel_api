<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'title', 'comment'
    ];
    /**
     * 多層次分類
     */
    public function products()
    {
        // return $this->hasMany('App\Models\Product', 'parent_id', 'id');
    }
    
}
