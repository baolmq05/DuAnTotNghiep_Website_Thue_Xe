<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Post;
 
class PostCategory extends Model
{
    //
    protected $fillable = [
        'name',
        'status'
    ];
}
