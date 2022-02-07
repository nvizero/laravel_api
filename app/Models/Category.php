<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $fillable = [
        'title', 'parent_id','is_show','sort'
    ];
    /**
     * 多層次分類
     */
    public function childs()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }

    public function style1()
    {
        return $this->hasMany('App\Models\CategoryStyle1', 'category_id', 'id');
    }

    public function style2()
    {
        return $this->hasMany('App\Models\CategoryStyle2', 'category_id', 'id');
    }
}
