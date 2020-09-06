<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatedAccountService extends Model
{
    //
    protected $table = "affiliated_account_services";

    public function rating_plan()
    {

        return $this->belongsTo(RatingPlan::class, 'rating_plan_id', 'id');

    }

    public function advance_line()
    {

        return $this->hasMany(AdvanceLine::class, 'affiliated_account_service_id', 'id');

    }

    public function company_affiliated()
    {

        return $this->belongsTo(AffiliatedCompanyRole::class, 'company_affiliated_id', 'affiliated_company_id');

    }

    public function affiliated_content_account_service()
    {

        return $this->hasMany(AffiliatedContentAccountService::class, 'affiliated_account_service_id', 'id');

    }
}
