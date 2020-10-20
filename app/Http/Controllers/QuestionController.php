<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Rating;
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
            if(isset($request->id)) {
                $question = Question::where([
                    ['sequence_id', $request->sequence_id], 
                    ['moment_id', $request->moment_id], 
                    ['section', $request->section],
                    ['id', $request->id]
                ])->first();
            }
            else {
                $question = new Question();
                $question->sequence_id = $request->sequence_id;
                $question->moment_id = $request->moment_id;
                $question->section = $request->section;
            }
            
            $question->experience_id = $request->experience_id;
            $question->order = $request->order;
            $question->title = $request->title;
            $question->objective = $request->objective;
            $question->concept = $request->concept;
            $question->options = $request->options;
            $question->isHtml = $request->isHtml;
            $question->review = $request->review;
            $question->type_answer = $request->type_answer;
            $question->save();
            
            return response()->json(['data' => $question, 'message', 'Pregunta registrada o actualizada'], 200);
            
        } else {
            if (!@json_decode($request->options))
                return response()->json(['data' => '', 'message', 'El formato para registrar o actualizar los datos de preguntas no es el correcto'], 200);
            return response()->json(['data' => '', 'message', 'El formato para registrar o actualizar los datos de respuestas no es el correcto'], 200);
        }
        


    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove_questions(Request $request)
    {   $question = Question::whereIn('id', $request->questions_ids)
                ->where('sequence_id',$request->sequence_id)
                ->delete();
        return response()->json(['data' => $question, 'message', '['.$question.'] Preguntas elimnadas correctamente'], 200);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function get_questions (Request $request){
       $question = Question::where('sequence_id',$request->sequence_id)
                   ->where('moment_id',$request->moment_id)
                   ->where('experience_id',$request->experience_id)
                   ->orderBy('order','ASC')
                   ->get();
       $student = $request->user('afiliadoempresa');                
       $rating = Rating::where([
            ['affiliated_account_service_id',$request->accountServiceId],
            ['sequence_id',$request->sequence_id],
            ['moment_id',$request->moment_id],
            ['experience_id',$request->experience_id],
            ['student_id',$student->id],
        ])->first();
        
        return response()->json(['rating'=>$rating,'data'=>$question],200);

    }

}
