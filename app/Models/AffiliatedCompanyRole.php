<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatedCompanyRole extends Model
{
    //
    protected $table = "affiliated_company_roles";

    protected $fillable = [
        'id',
        'affiliated_company_id',
        'rol_id',
        'company_id',
    ];

    public function conection_students()
    {

        return $this->hasMany(ConectionAffiliatedStudents::class, 'student_company_id', 'id');

    }

    public function company()
    {

        return $this->belongsTo(Companies::class, 'company_id', 'id');

    }

    public function rol()
    {

        return $this->belongsTo(Roles::class, 'rol_id', 'id');

    }

    public function conection_tutor()
    {

        return $this->hasMany(ConectionAffiliatedStudents::class, 'tutor_company_id', 'id');

    }

    public function retrive_afiliado_empresa()
    { 
        return $this->belongsTo(AfiliadoEmpresa::class, 'affiliated_company_id', 'id');

    }
}
