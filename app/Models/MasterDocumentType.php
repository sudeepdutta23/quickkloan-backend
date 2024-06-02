<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterDocumentType extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'master_document_type';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    public function individual_document(): BelongsTo
    {
        return $this->belongsTo(IndividualDocument::class);
    }
}
