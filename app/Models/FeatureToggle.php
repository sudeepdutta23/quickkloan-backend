<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureToggle extends Model
{
    use HasFactory;

    protected $table = 'feature_toggle';

    protected $guarded = [];

    
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    

}
