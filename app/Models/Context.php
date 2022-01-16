<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Context extends Model
{
    use HasFactory;
    protected $table = 'context';

    protected $fillable = [
        'title', 'content', 'short'
    ];
    
}
