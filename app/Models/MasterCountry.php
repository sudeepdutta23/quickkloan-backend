<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterCountry extends Model
{
    use HasFactory;   

    protected $guarded = [];
    protected $table = 'master_country';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
  

    public function state(): HasMany
    {
        return $this->hasMany(MasterState::class,'country_id');
    }

}
