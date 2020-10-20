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
        'experience_id',
        'weighted',
        'letter',
        'section',
        'attempts'
    ];
    
    use \Awobaz\Compoships\Compoships;

    public function answers()
    {
        //return $this->hasMany(Answer::class, 'experience_id', 'experience_id');
        return $this->hasMany(Answer::class, ['experience_id', 'student_affiliated_company_id','affiliated_account_service_id'], ['experience_id', 'student_id', 'affiliated_account_service_id']);

    }
}
