<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndividualComment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'individual_comments';

    const CREATED_AT = 'commentedOn';
    const UPDATED_AT = 'updatedAt';

}
