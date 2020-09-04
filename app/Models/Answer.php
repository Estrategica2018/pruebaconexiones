<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    //
    protected $table = "answers";
    protected $fillable = [
        'student_affiliated_company_id',
        'company_id',
        'affiliated_account_service_id',
        'question_id',
        'experience_id',
        'answer',
        'feedback',
        'teacher_affiliated_company_id',
        'date_evaluation',
        'concept',
    ];
	
	use \Awobaz\Compoships\Compoships;


    public function question()
    {

        return $this->belongsTo(Question::class, 'question_id', 'id');

    }
}
