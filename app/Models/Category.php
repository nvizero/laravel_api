<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $fillable = [
        'title', 'parent_id'
    ];
    /**
     * 多層次分類
     */
    public function childs()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }
}
