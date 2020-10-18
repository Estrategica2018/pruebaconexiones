<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use App\Models\ConectionAffiliatedStudents;
use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use App\Notifications\MyResetPassword;
use App\Notifications\WelcomeMail;
use App\Models\ShoppingCart;
use App\Models\AdvanceLine;
use App\Models\AffiliatedCompanyRole;
use Illuminate\Auth\Passwords\PasswordBroker;

class AfiliadoEmpresa extends Model
{
    //
    use Notifiable;

    protected $table = "afiliado_empresas";

    protected $fillable = [
        'user_name',
        'name',
        'last_name',
        'email',
        'url_image',
        'country_id',
        'department_id',
        'city_id',
        'city_name',
        'password'
    ];
    // protected $guarded = ['id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Roles', 'afiliado_empresa_roles', 'afiliado_empresa_id', 'rol_id')->withTimestamps();
    }

    public function authorizeRoles($roles)
    {
        if ($this->hasAnyRole($roles)) {
            return true;
        }
        abort(401, 'Esta acción no está autorizada.');
    }

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($rol)
    {
        $role = cache()->tags('connection_roles_redis')->rememberForever('roles_redis', function () {
            return Roles::all();//where('name',$rol) ->first();
        });
        $company = cache()->tags('connection_companies_redis')->rememberForever('companies_redis', function () {
            return Companies::all();//where('name',$rol) ->first();
        });
        $company = $company->where('nick_name', session('name_company'))->first();
        $role = $role->where('name', $rol)->first();
        if (AffiliatedCompanyRole::where([
            ['affiliated_company_id', auth('afiliadoempresa')->user()->id],
            ['rol_id', $role->id],
            ['company_id', $company->id],
        ])->first()) {
            return true;
        }

        return false;
    }

    public function companies()
    {
        return $this->belongsToMany('App\Models\Companies', 'affiliated_company_roles', 'affiliated_company_id', 'company_id')->withTimestamps();
    }

    public function hasCompany($company)
    {
        if ($this->companies()->where('nick_name', $company)->first()) {
            return true;
        }
        return false;
    }

    public function company_teacher_rol()
    {

        return $this->hasMany(AffiliatedCompanyRole::class, 'affiliated_company_id', 'id');

    }

    public function country()
    {

        return $this->belongsTo(Country::class, 'country_id', 'id');

    }

    public function city()
    {

        return $this->belongsTo(City::class, 'city_id', 'id');

    }

    public function affiliated_company()
    {

        return $this->hasMany(AffiliatedCompanyRole::class, 'affiliated_company_id', 'id');
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token, session('name_company'), session('rol')));
    }

    public function sendWelcomeNotification($rol)
    {
        $token = app(PasswordBroker::class)->createToken($this);
        $this->notify(new WelcomeMail($token, session('name_company'), $rol));
    }

    public function company_name()
    {
        return session('name_company');
    }

    public function company_id()
    {
        return session('company_id');
    }

    public function affiliated_account_services()
    {

        return $this->hasMany(AffiliatedAccountService::class, 'company_affiliated_id', 'id');
    }

    public function last_payment_date()
    {
        $payment_status_success = 3;
        $user_id = $this->id;
        $lastShoppingCart = ShoppingCart::where(['company_affiliated_id' => $user_id, 'payment_status_id' => $payment_status_success])
            ->orderBy('payment_init_date', 'DESC')->first();
        if (isset($lastShoppingCart)) {
            return $lastShoppingCart->payment_init_date;
        } else {
            return null;
        }
    }

    public function first_last_access()
    {
        $user_id = $this->id;

        $dates = AdvanceLine::with(['affiliated_account_service' => function ($query) {
            $query->where('init_date', '>=', date('Y-m-d'))
                ->where('end_date', '<=', date('Y-m-d', strtotime('+ 1 day')));
        }])->where('affiliated_company_id', $user_id)->get();

        return ['first' => $dates->min('updated_at'), 'last' => $dates->max('updated_at')];
    }

    public function kidSelected()
    {
        $user_id = $this->id;
        $rol_id = AffiliatedCompanyRole::select('id')->where([
            ['affiliated_company_id', $user_id],
            ['rol_id', 1]//estudiante
        ])->first()->id;
        $kidSelected = ConectionAffiliatedStudents::select('age_stage')->where('student_company_id', $rol_id)->get()->first();
        if( $kidSelected !== null)
            return $kidSelected['age_stage'];
        return '';
    }

    public function emailForContact()
    {
        $user_id = $this->id;
        if ($this->hasRole('student')) {

            $rol_id = AffiliatedCompanyRole::select('id')->where([
                ['affiliated_company_id', $user_id],
                ['rol_id', 1]//estudiante
            ])->first()->id;
 
            $parent_family = ConectionAffiliatedStudents::with(['parent_family.retrive_afiliado_empresa'])->where('student_company_id', $rol_id)->get()->first();
           
            if($parent_family) {
                return $parent_family->parent_family->retrive_afiliado_empresa->email;
            }
            else return '';
        }
        else {
            return $this->email;
        }
   

    }

    public function nameFamiliar()
    {
        $user_id = $this->id;
        if ($this->hasRole('student')) {

            $rol_id = AffiliatedCompanyRole::select('id')->where([
                ['affiliated_company_id', $user_id],
                ['rol_id', 1]//estudiante
            ])->first()->id;

            $parent_family = ConectionAffiliatedStudents::with(['parent_family.retrive_afiliado_empresa'])->where('student_company_id', $rol_id)->get()->first();

            if($parent_family) {
                return $parent_family->parent_family->retrive_afiliado_empresa->name.' '.$parent_family->parent_family->retrive_afiliado_empresa->last_name;
            }
            else return '';
        }
        else {
            return $this->name.' '.$this->last_name;
        }


    }

}
