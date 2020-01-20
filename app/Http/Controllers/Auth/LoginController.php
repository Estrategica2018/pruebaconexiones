<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AfiliadoEmpresa;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->stateless()->user();

        $afiliadoempresa = AfiliadoEmpresa::where('provaider_id',$user->id)->first();

        if($afiliadoempresa === null){
            $afiliadoempresa = new AfiliadoEmpresa();
            $afiliadoempresa->nombre = $user->name;
            $afiliadoempresa->correo = $user->email;
            $afiliadoempresa->provaider_id = $user->id;
            $afiliadoempresa->save();
        }

        Auth::guard('afiliadoempresa')->login($afiliadoempresa);
        return redirect($this->redirectTo);
    }
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProviderGmail()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallbackGmail()
    {
        $user = Socialite::driver('google')->stateless()->user();
        $afiliadoempresa = AfiliadoEmpresa::where('provaider_id',$user->id)->first();

        if($afiliadoempresa === null){
            $afiliadoempresa = new AfiliadoEmpresa();
            $afiliadoempresa->nombre = $user->name;
            $afiliadoempresa->correo = $user->email;
            $afiliadoempresa->provaider_id = $user->id;
            $afiliadoempresa->save();
        }

        Auth::guard('afiliadoempresa')->login($afiliadoempresa);
        return redirect($this->redirectTo);
    }
}
