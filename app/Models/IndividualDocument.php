<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndividualDocument extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'individual_document';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function individual(): BelongsTo
    {
        return $this->belongsTo(Individual::class);
    }


}
