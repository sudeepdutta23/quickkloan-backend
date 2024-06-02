<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterCity extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'master_city';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function country(): BelongsTo
    {
        return $this->belongsTo(MasterState::class);
    }
}
