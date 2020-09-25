<?php

namespace App\Http\Controllers\Auth;

use App\Models\AffiliatedAccountService;
use App\Models\AffiliatedCompanyRole;
use App\Models\AffiliatedContentAccountService;
use App\Models\CompaniesAffiliated;
use App\Models\AfiliadoEmpresa;
use App\Models\RatingPlan;
use App\Models\ShoppingCart;
use App\Traits\CreateUserRelations;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use CreateUserRelations;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'user_name' => ['required', 'string', 'max:255','unique:afiliado_empresas'],
            'name' => ['required', 'string', 'min:4', 'max:255'],
            'last_name' => ['required', 'string', 'min:4', 'max:255'],
            'country_id' => ['required', 'string', 'max:255'],
            'city' => ['string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:afiliado_empresas'],
            //'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        session(['name_company' => 'conexiones']);
        session(['company_id' => 1]);
        $asignarNombreUsuario = false;

        $name_user = $this->name_user_affiliated($data);
        $afiliado_empresa = AfiliadoEmpresa::create([
            'user_name' => $name_user,
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($name_user),
            'country_id' => $data['country_id'],
            'department_id' => $data['department_id'],
            'city_id' => $data['city_id'],
            'city_name' => $data['city_name'],
        ]);


        $affiliated_company_role = new AffiliatedCompanyRole();
        $affiliated_company_role->affiliated_company_id = $afiliado_empresa->id;
        $affiliated_company_role->rol_id = 3;
        $affiliated_company_role->company_id = 1;
        $affiliated_company_role->save();

        $afiliado_empresa->sendWelcomeNotification($affiliated_company_role->rol_id);
        $this->redirectTo = 'conexiones/tutor';

        session()->pull('free_rating_plan_id'); //remove cache to session
        if (isset($data['free_rating_plan_id'])) {
            $free_rating_plan_id = $data['free_rating_plan_id'];
            $ratingPlanFree = RatingPlan::find($free_rating_plan_id);
            if ($ratingPlanFree && $ratingPlanFree->is_free) {
                $this->addFreeRatingPlan($ratingPlanFree, $afiliado_empresa);
                $this->redirectTo = 'conexiones/tutor/productos';
            }
        }
        if (session_id() == "") {
            session_start();
        }

        $update = ShoppingCart:: where('session_id', session_id())
            ->where('payment_status_id', 1)
            ->update(['company_affiliated_id' => $afiliado_empresa->id, 'session_id' => 'NULL']);

        if ($update > 0) {
            $this->redirectTo = 'carrito_de_compras';
        }

        return $afiliado_empresa;
    }


    protected function guard()
    {
        return Auth::guard('afiliadoempresa');
    }

    public function show_register(Request $request, $errorEmailSocial = false, $email = 'empty')
    {

        $free_rating_plan_id = $request->session()->get('free_rating_plan_id');
        $status_validation_free_plan = false;
        if($request->session()->has('status_validation_free_plan')){
            $status_validation_free_plan = true;
        } 
        if (session_id() == "") {
            session_start();
        }

        $shoppingCart = ShoppingCart:: where('session_id', session_id())
            ->where('payment_status_id', 1)->count();

        $redirect_to_shoppingcart = $shoppingCart > 0;

        return view('auth.register',
            [
                'free_rating_plan_id' => $free_rating_plan_id,
                'redirect_to_shoppingcart' => $redirect_to_shoppingcart,
                'errorEmailSocial' => $errorEmailSocial,
                'email' => $email,
                'status_validation_free_plan'=> $status_validation_free_plan
            ]);
    }

    public static function addFreeRatingPlan($ratingPlanFree, $afiliado_empresa)
    {

        if ($ratingPlanFree->is_free) {
            if ($ratingPlanFree->moment_free_ids == null || $ratingPlanFree->moment_free_ids == '') {
                $affiliatedAccountService = new AffiliatedAccountService();
                $affiliatedAccountService->company_affiliated_id = $afiliado_empresa->id;
                $affiliatedAccountService->rating_plan_id = $ratingPlanFree->id;
                $affiliatedAccountService->rating_plan_type = $ratingPlanFree->type_rating_plan_id;;
                $affiliatedAccountService->company_sequence_id = $ratingPlanFree->sequence_free_id;
                $affiliatedAccountService->init_date = date('Y-m-d');
                $affiliatedAccountService->end_date = date('Y-m-d', strtotime('+ ' . $ratingPlanFree->days . ' day'));
                $affiliatedAccountService->save();
                $affiliatedContentAccountService = new AffiliatedContentAccountService();
                $affiliatedContentAccountService->affiliated_account_service_id = $affiliatedAccountService->id;
                $affiliatedContentAccountService->type_product_id = $ratingPlanFree->type_rating_plan_id;;
                $affiliatedContentAccountService->sequence_id = $ratingPlanFree->sequence_free_id;
                $affiliatedAccountService->init_date = date('Y-m-d');
                $affiliatedAccountService->end_date = date('Y-m-d', strtotime('+ ' . $ratingPlanFree->days . ' day'));
                $affiliatedContentAccountService->save();

            } else {
                $ids = explode(',', $ratingPlanFree->moment_free_ids);

                $affiliatedAccountService = new AffiliatedAccountService();
                $affiliatedAccountService->company_affiliated_id = $afiliado_empresa->id;
                $affiliatedAccountService->rating_plan_id = $ratingPlanFree->id;
                $affiliatedAccountService->rating_plan_type = $ratingPlanFree->type_rating_plan_id;
                $affiliatedAccountService->init_date = date('Y-m-d');
                $affiliatedAccountService->end_date = date('Y-m-d', strtotime('+ ' . $ratingPlanFree->days . ' day'));
                $affiliatedAccountService->save();
                foreach ($ids as $id) {
                    $affiliatedContentAccountService = new AffiliatedContentAccountService();
                    $affiliatedContentAccountService->affiliated_account_service_id = $affiliatedAccountService->id;
                    $affiliatedContentAccountService->type_product_id = $ratingPlanFree->type_rating_plan_id;
                    $affiliatedContentAccountService->sequence_id = $ratingPlanFree->sequence_free_id;
                    $affiliatedContentAccountService->moment_id = $id;
                    $affiliatedContentAccountService->save();
                }
            }
        }
    }

    public function validate_registry_free_plan(Request $request, $rating_plan_id)
    {

        if($request->ajax()){
            $ratingPlanFree = RatingPlan::where('is_free',1)->first();
            if (!$ratingPlanFree || !$ratingPlanFree->is_free) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Plan gratuito no encontrado'
                ]);
                    //view('page404', ['message' => 'Plan gratuito no encontrado']);
            }
            $afiliado_empresa = $request->user('afiliadoempresa');
            if ($afiliado_empresa) {
                //TODO:  si el afliiado es diferente a familiar (tutor), invitar a registro

                if ($afiliado_empresa->hasRole('tutor')) {
                    $hasFreePlan = AffiliatedAccountService::whereHas('rating_plan',function ($query){
                        return $query->where('is_free',1);
                    })->
                    where('company_affiliated_id', '=', $afiliado_empresa->id)
                        ->where([
                            ['init_date', '<=', date('Y-m-d') ],
                            ['end_date', '>=', date('Y-m-d') ]
                        ])->first();
                    if($hasFreePlan === null){
                        $this->addFreeRatingPlan($ratingPlanFree, $afiliado_empresa);
                        return response()->json([
                            'status' => 'successfull',
                            'message' => 'El plan ha sido registrado correctamente, los estudiantes inscritos puden acceder a este plan'
                        ]);
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message' => 'El plan gratuito no ha expirado, este plan expira para la fecha '.$hasFreePlan->end_date
                        ]);
                    }

                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No tiene permisos para acceder al plan gratuito'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No tiene permisos para acceder al plan gratuito'
                ]);
            }
            //return "AJAX";
        }else{
            $ratingPlanFree = RatingPlan::find($rating_plan_id);

            if (!$ratingPlanFree || !$ratingPlanFree->is_free) {
                return view('page404', ['message' => 'Plan gratuito no encontrado']);
            }
            $afiliado_empresa = $request->user('afiliadoempresa');
            if ($afiliado_empresa) {
                //TODO:  si el afliiado es diferente a familiar (tutor), invitar a registro

                if ($afiliado_empresa->hasRole('tutor')) {
                    $hasFreePlan = AffiliatedAccountService::whereHas('rating_plan',function ($query){
                        return $query->where('is_free',1);
                    })->
                    where('company_affiliated_id', '=', $afiliado_empresa->id)
                        ->where([
                            ['init_date', '<=', date('Y-m-d') ],
                            ['end_date', '>=', date('Y-m-d') ]
                        ])->first();
                    if($hasFreePlan === null){
                        session(['status_validation_free_plan' => 1]);
                        $this->addFreeRatingPlan($ratingPlanFree, $afiliado_empresa);

                    }else{
                        session(['status_validation_free_plan' => 2]);
                    }
                    //$this->addFreeRatingPlan($ratingPlanFree, $afiliado_empresa);
                    return redirect('conexiones/tutor');
                } else {
                    session(['status_validation_free_plan' => false]);
                    $request->session()->put('free_rating_plan_id', $rating_plan_id);
                    return redirect()->action('Auth\RegisterController@show_register');
                }
            } else {
                session(['status_validation_free_plan' => false]);
                $request->session()->put('free_rating_plan_id', $rating_plan_id);
                return redirect()->action('Auth\RegisterController@show_register');
            }
        }

    }

}
