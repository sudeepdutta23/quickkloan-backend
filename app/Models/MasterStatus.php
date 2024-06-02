<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterStatus extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'master_status';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function stage(): BelongsTo
    {
        return $this->belongsTo(MasterStage::class);
    }
}
