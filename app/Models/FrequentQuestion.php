<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrequentQuestion extends Model
{
    //
    protected $table = "frequent_questions";
    
    protected $fillable = [
        'id',
        'question',
        'answer'
    ];

}
