<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogHashTag extends Model
{
    use HasFactory;

    protected $table = 'blog_hash_tags';

    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';


    
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
   
}
