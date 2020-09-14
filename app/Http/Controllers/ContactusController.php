<?php

namespace App\Http\Controllers;

use App\Mail\SendContactus;
use App\Models\Contacus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Class ContactusController
 * @package App\Http\Controllers
 */
class ContactusController extends Controller
{
    //

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_email_contactus(Request $request)
    {

        try {
            $var = $request->all();
            $contacus = new Contacus();
            $contacus->name = $request->name;
            $contacus->email = $request->email;
            $contacus->case = $request->affair;
            $contacus->message = $request->message;
            $contacus->save();
            $contacus->filing_number = 4000 + $contacus->id;
            $contacus->save();
            $var['contacus_id'] = 4000 + $contacus->id;
            $var['user_notification'] = 1;
            Mail::to(['contacto@educonexiones.com'])->send(new SendContactus($var));
            $var['user_notification'] = 2;
            Mail::to($request->email)->send(new SendContactus($var));
            return response()->json([
                ['message' => 'El mensaje ha sido enviado satisfactoriamente. A tu correo llegará un número de radicado para seguimiento del proceso.'],
                ['status' => 'success']

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                ['message' => 'No se ha podido notificar su mesaje, intente de nuevo, gracias' . $e],
                ['status' => 'error']
            ], 500);
        }

    }
}
