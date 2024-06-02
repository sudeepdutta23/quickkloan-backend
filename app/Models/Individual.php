<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Individual extends Authenticatable
{
    use HasFactory, HasApiTokens;
    protected $guarded = [];
    protected $table = 'individual';
    protected $guard = 'individual';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function advance(): HasOne
    {
        return $this->hasOne(IndividualAdvance::class,'individualId');
    }

    public function address(): HasOne
    {
        return $this->hasOne(IndividualAddress::class,'individualId');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(IndividualDocument::class,'individualId');
    }


}
