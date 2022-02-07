<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryStyle2 extends Model
{
    use HasFactory;

    protected $table = 'category_styles2';

    protected $fillable = [
        'name', 'category_id'
    ];
    /**
     * 多層次分類
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
