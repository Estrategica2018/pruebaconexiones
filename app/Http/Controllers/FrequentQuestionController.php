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
    
}
