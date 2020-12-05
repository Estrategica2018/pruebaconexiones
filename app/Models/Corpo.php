<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corpo extends Model
{
    protected $table = "corpo";

    protected $fillable = [
        'remetente_name',
        'remetente_doc_type',
        'remetente_doc_number',
        'remetente_email',
        'subject_to',
        'code_in',
        'request_at',
        'status',
        'code_out',
        'response_date',
        'response_hour',
        'serie',
        'id'
    ];
    
    
}
