<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Close session and return url
     *
     * @return void
     */
    public function close_session(Request $request)
    {
        
        $user = auth('afiliadoempresa')->user();  
        if ($user) {
            $nickCompany = auth('afiliadoempresa')->user()->company_name();
            $redirectTo = route('loginform',$nickCompany);  
            Auth::guard('afiliadoempresa')->logout();
            $request->session()->flush();
            session(['name_company' => $nickCompany]);
            return response()->json(['url'=> $redirectTo], 200);
        } else {
            return response()->json(['message', 'Usuario sin sesión activa'], 200);
        }
    }
}
