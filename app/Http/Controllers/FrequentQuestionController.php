<?php

namespace App\Http\Controllers;

use App\Models\FrequentQuestion;
use Illuminate\Http\Request;
use App\Mail\SendFrequentQuestions;
use Illuminate\Support\Facades\Mail;

class FrequentQuestionController extends Controller
{
    //

    public function get_frequent_questions (Request $request){

        $frequentQuestions = FrequentQuestion::all();
        return response()->json(['data' => $frequentQuestions], 200);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_email_frequent_questions (Request $request)
    {

        try {
            $var = $request->all();        
            Mail::to([env('EMAIL_OPERATION')])
            ->cc($request->email)
            ->send(new SendFrequentQuestions($var));            
            return response()->json([
                ['message' => 'El mensaje ha sido enviado satisfactoriamente al correo <'.env('MAIL_USERNAME').'>'],
                ['status' => 'success']
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                ['message' => 'No se ha podido notificar tu mensaje, por favor intenta de nuevo, gracias' . $e],
                ['status' => 'error']
            ], 500);
        }

    }

}
