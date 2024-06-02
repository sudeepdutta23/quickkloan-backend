<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
class Blog extends Model
{
    use HasFactory;
    // SoftDeletes;

    protected $table = 'blogs';

    protected $guarded = [];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';



    public function media()
    {
        return $this->hasMany(BlogImage::class,'blog_id');
    }

}
