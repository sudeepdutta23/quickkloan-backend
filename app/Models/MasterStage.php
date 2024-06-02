<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterStage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'master_stage';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function status(): HasMany
    {
        return $this->hasMany(MasterStatus::class,'stage_id');
    }
}
