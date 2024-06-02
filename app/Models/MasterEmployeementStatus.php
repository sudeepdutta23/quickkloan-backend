<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class MasterEmployeementStatus extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'master_employment_status';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
}
