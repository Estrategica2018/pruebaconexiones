<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\Companies;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm($empresa, $rol, $email=null)
    {
        //dd($empresa);
        $empresa = Companies::where('nick_name', $empresa)->first();
        session(['rol' => $rol]);
        // dd($empresa,$rol);
        return strtolower($empresa->name) === 'conexiones' ?
            view('auth.passwords.email', ['company' => $empresa, 'rol' => $rol, 'email'=>$email]) :
            view('auth.passwords.emailCompany', ['company' => $empresa]);
    }

    public function showResetForm(Request $request, $empresa = null, $token = null, $rol = null, $email = null)
    {
        $empresa = Companies::where('nick_name', $empresa)->first();

        return strtolower($empresa->name) === 'conexiones' ?
            view('auth.passwords.reset')->with(
                ['token' => $token, 'empresa' => $empresa, 'email' => $email, 'rol' => $rol]
            ) : view('auth.passwords.resetCompany')->with(
                ['token' => $token, 'empresa' => $empresa, 'email' => $email, 'rol' => $rol]
            );;
    }
    /*
        public function sendResetLinkEmail(){

        }
    */
}
