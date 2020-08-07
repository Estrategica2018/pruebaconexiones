<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

/**
 * Class QuestionController
 * @package App\Http\Controllers
 */
class QuestionController extends Controller
{
    //
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register_update_question(Request $request)
    {
        if (@json_decode($request->options) && @json_decode($request->review)) {
            $question = Question::updateOrCreate(
                ['sequence_id' => $request->sequence_id, 'moment_id' => $request->moment_id, 'experience_id' => $request->experience_id, 'order' => $request->order, 'section' => $request->section],
                ['title' => $request->title,'objective' => $request->objective,'concept' => $request->concept, 'options' => $request->options, 
                 'isHtml' => $request->isHtml, 'review' => $request->review, 'type_answer' => $request->type_answer]
            );
        } else {
            if (!@json_decode($request->options))
                return response()->json(['data' => '', 'message', 'El formato para registrar o actualizar los datos de preguntas no es el correcto'], 200);
            return response()->json(['data' => '', 'message', 'El formato para registrar o actualizar los datos de respuestas no es el correcto'], 200);
        }
        return response()->json(['data' => $question, 'message', 'Pregunta registrada o actualizada'], 200);

    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove_question(Request $request)
    {
        $question = Question::where(
                ['sequence_id' => $request->sequence_id, 
                 'moment_id' => $request->moment_id]
            )->delete();
        return response()->json(['data' => $question, 'message', 'Preguntas elimnadas correctamente'], 200);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function get_questions (Request $request){
        //sin calificación
        $question = Question::where('sequence_id',$request->sequence_id)
                   ->where('moment_id',$request->moment_id)
                   ->where('experience_id',$request->experience_id)
                   ->orderBy('order','ASC')
                   ->get();
       // dd($question);
        return response()->json(['data'=>$question],200);

    }

}
