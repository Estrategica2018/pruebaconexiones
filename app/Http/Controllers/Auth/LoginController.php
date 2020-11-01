<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AffiliatedCompanyRole;
use App\Models\AfiliadoEmpresa;
use App\Models\RatingPlan;
use App\Models\ShoppingCart;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    private $rol = null;
    private $redirectTo = '/';
    /**
     * Where to redirect users after login.
     *
     * @var string
     */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $registerController;

    public function __construct(RegisterController $registerController)
    {
        $this->registerController = $registerController;
        $this->middleware('guest')->except('logout');

    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(Request $request, $rol, $socialAction)
    {
        session(['social_action' => $socialAction]);
        if (isset($request->free_rating_plan_id)) {
            session(['free_rating_plan_id' => $request->free_rating_plan_id]);
        }
        
        $this->rol = decrypt($rol);
        $this->rolLogin();
        session(['name_company' => 'conexiones']);
        session(['company_id' => 1]);
        session(['redirect_to_portal' => $this->redirectTo]);
        return Socialite::driver('facebook')->with(['request_type' => 'reauthenticate','access_type' => 'offline',"prompt" => "consent select_account"])->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $socialAction = session()->pull('social_action');
        if ($socialAction === 'register') {
            $user = Socialite::driver('facebook')->stateless()->user();

            if (AfiliadoEmpresa::where('email', $user->email)->first() === null) {
                $afiliadoempresa = $this->createAfiliado($user, 'facebook');
                Auth::guard('afiliadoempresa')->login($afiliadoempresa);
                $free_rating_plan_id = session()->pull('free_rating_plan_id');
                if ($free_rating_plan_id) {
                    $ratingPlan = RatingPlan::find($free_rating_plan_id);
                    if ($ratingPlan->is_free) {
                        $this->registerController->addFreeRatingPlan($ratingPlan, $afiliadoempresa);

                    }
                }
                if (session_id() == "") {
                    session_start();
                }
                $update = ShoppingCart:: where('session_id', session_id())
                    ->where('payment_status_id', 1)
                    ->update(['company_affiliated_id' => $afiliadoempresa->id, 'session_id' => 'NULL']);

                
                if ($update > 0) {
                    return redirect()->route('shoppingCart');
                }

                $redirect_to_portal = session('redirect_to_portal');
                return redirect()->route($redirect_to_portal, ['empresa' => 'conexiones']);
            } else {
                Auth::guard('afiliadoempresa')->login(AfiliadoEmpresa::where('email', $user->email)->first());
                $user_id = auth('afiliadoempresa')->user()->id;
                if (session_id() == "") {
                    session_start();
                }
                $update = ShoppingCart:: where('session_id', session_id())
                    ->where('payment_status_id', 1)
                    ->update(['company_affiliated_id' => $user_id, 'session_id' => 'NULL']);
                if ($update > 0) {
                    $redirect_to_portal = 'shoppingCart';
                } else {
                    $redirect_to_portal = session('redirect_to_portal');
                }
                return redirect()->route($redirect_to_portal, ['empresa' => 'conexiones']);
            }
        }
        if ($socialAction === 'login') {
            $user = Socialite::driver('facebook')->stateless()->user();
            $afiliadoempresa = AfiliadoEmpresa::where('email', $user->email)->first();

            if ($afiliadoempresa !== null) {
                Auth::guard('afiliadoempresa')->login($afiliadoempresa);
            } else {
                $afiliadoempresa = $this->createAfiliado($user, 'gmail');
                Auth::guard('afiliadoempresa')->login($afiliadoempresa);
            }
            $redirect_to_portal = session('redirect_to_portal');
            return redirect()->route($redirect_to_portal, ['empresa' => 'conexiones']);
        }
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProviderGmail(Request $request, $rol, $socialAction)
    {
        $this->rol = decrypt($rol);
        $this->rolLogin();
        session(['name_company' => 'conexiones']);
        session(['company_id' => 1]);
        session(['social_action' => $socialAction]);
        if (isset($request->free_rating_plan_id)) {
            session(['free_rating_plan_id' => $request->free_rating_plan_id]);
        }
        session(['redirect_to_portal' => $this->redirectTo]);
        return Socialite::driver('google')->with(['request_type' => 'reauthenticate','access_type' => 'offline',"prompt" => "consent select_account"])->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallbackGmail()
    {
        $socialAction = session()->pull('social_action');
        if ($socialAction === 'register') {
            $user = Socialite::driver('google')->stateless()->user();
            if (AfiliadoEmpresa::where('email', $user->email)->first() === null) {
                $afiliadoempresa = $this->createAfiliado($user, 'gmail');
                Auth::guard('afiliadoempresa')->login($afiliadoempresa);
                $redirect_to_portal = session('redirect_to_portal');

                $free_rating_plan_id = session()->pull('free_rating_plan_id');
               
                if ($free_rating_plan_id) {
                    $ratingPlan = RatingPlan::find($free_rating_plan_id);
                    if ($ratingPlan->is_free) {
                        $this->registerController->addFreeRatingPlan($ratingPlan, $afiliadoempresa);
                        $redirect_to_portal = 'tutor.products';
                    }
                }

                if (session_id() == "") {
                    session_start();
                }
                $updates = ShoppingCart:: where('session_id', session_id())
                    ->where('payment_status_id', 1)
                    ->update(['company_affiliated_id' => $afiliadoempresa->id, 'session_id' => 'NULL']);

                if ($updates > 0) {
                    return redirect()->route('shoppingCart');
                } else {

                    return redirect()->route($redirect_to_portal, ['empresa' => 'conexiones']);
                }
            } else {
                Auth::guard('afiliadoempresa')->login(AfiliadoEmpresa::where('email', $user->email)->first());
                $user_id = auth('afiliadoempresa')->user()->id;
                if (session_id() == "") {
                    session_start();
                }
                $update = ShoppingCart:: where('session_id', session_id())
                    ->where('payment_status_id', 1)
                    ->update(['company_affiliated_id' => $user_id, 'session_id' => 'NULL']);
                if ($update > 0) {
                    $redirect_to_portal = 'shoppingCart';
                } else {
                    $redirect_to_portal = session('redirect_to_portal');
                }
                return redirect()->route($redirect_to_portal, ['empresa' => 'conexiones']);
            }
        }
        if ($socialAction === 'login') {
            $user = Socialite::driver('google')->stateless()->user();
            $afiliadoempresa = AfiliadoEmpresa::where('email', $user->email)->first();
            if ($afiliadoempresa !== null) {
                Auth::guard('afiliadoempresa')->login($afiliadoempresa);
            } else {
                $afiliadoempresa = $this->createAfiliado($user, 'gmail');
                Auth::guard('afiliadoempresa')->login($afiliadoempresa);
            }
            $redirect_to_portal = session('redirect_to_portal');
            return redirect()->route($redirect_to_portal, ['empresa' => 'conexiones']);
        }

    }

    public function createAfiliado($user, $tipoProvider)
    {
        ($tipoProvider === 'gmail') ?
            $afiliadoempresa = AfiliadoEmpresa::whereHas('affiliated_company', function ($query) {
                $query->where([
                    ['rol_id', 3],
                    ['company_id', 1]
                ]);
            })->where(function ($query) use ($user) {
                $query->where('provider_google', $user->id)->orWhere('email', $user->email);
            })->first() :

            $afiliadoempresa = AfiliadoEmpresa::whereHas('affiliated_company', function ($query) {
                $query->where([
                    ['rol_id', 3],
                    ['company_id', 1]
                ]);
            })->where(function ($query) use ($user) {
                $query->where('provider_facebook', $user->id)->orWhere('email', $user->email)->first();
            })->first();

        if ($afiliadoempresa === null) {

            $afiliadoempresa = new AfiliadoEmpresa();
            $dataProvider = explode(' ', $user->name);
            $data = ['name' => $dataProvider[0], 'last_name' => $dataProvider[1]];
            $name_user = $this->name_user_affiliated($data);
            $afiliadoempresa->user_name = $name_user;
            $afiliadoempresa->name = $dataProvider[0];
            $afiliadoempresa->last_name = $dataProvider[1];
            $afiliadoempresa->email = $user->email;
            $afiliadoempresa->password = Hash::make($name_user);
            ($tipoProvider === 'gmail') ?
                $afiliadoempresa->provider_google = $user->id :
                $afiliadoempresa->provider_facebook = $user->id;
            $afiliadoempresa->save();
            $affiliated_company_role = new AffiliatedCompanyRole();
            $affiliated_company_role->affiliated_company_id = $afiliadoempresa->id;
            $affiliated_company_role->rol_id = 3;//tutor
            $affiliated_company_role->company_id = 1;//conexiones
            $affiliated_company_role->save();
            $afiliadoempresa->sendWelcomeNotification(3);// envio de parametro rol tutor/familiar (3)
        }
        return $afiliadoempresa;
    }

    public function rolLogin()
    {
        switch ($this->rol) {
            case 1:
                $this->redirectTo = "student";
                break;
            case 2:
                $this->redirectTo = "teacher";
                break;
            case 3:
                $this->redirectTo = "tutor";
                break;
        }
    }

    public function name_user_affiliated($data)
    {
        $data['name'] = preg_split('/\s+/', $data['name'])[0];
        $data['last_name'] = preg_split('/\s+/',$data['last_name'])[0];
        $name_user = $data['name'].$data['last_name'].'C';

        $asignarNombreUsuario = false;
        do {
            if (count(AfiliadoEmpresa::where('user_name', $name_user)->get())) {
                $name_user = $name_user . rand(0, 9);
            } else {
                $asignarNombreUsuario = true;
            }
        } while (!$asignarNombreUsuario);


        return $name_user;

    }
}
