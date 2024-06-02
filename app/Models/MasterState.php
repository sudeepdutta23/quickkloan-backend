<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;


class MasterState extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'master_state';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function country(): BelongsTo
    {
        return $this->belongsTo(MasterCountry::class);
    }

    public function city(): HasMany
    {
        return $this->hasMany(MasterCity::class,'state_id');
    }

}
