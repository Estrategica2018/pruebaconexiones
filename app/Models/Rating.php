<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    //
    protected $table = "ratings";   

    protected $fillable = [
        'student_id',
        'company_id',
        'affiliated_account_service_id',
        'sequence_id',
        'moment_id',
        'weighted',
        'letter',
        'section'
    ];
}
