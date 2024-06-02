<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class LeadIndividualMapping extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'lead_individual_mapping';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
}
