<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class MasterLoanType extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'master_loan_type';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
}
