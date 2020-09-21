<?php

namespace App\Http\Controllers;

use App\Models\AdvanceLine;  
use App\Models\AfiliadoEmpresa;  
use App\Models\Answer;
use App\Models\Rating;
use App\Models\Question; 
use App\Models\CompanySequence;
use App\Models\SequenceMoment;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AffiliatedAccountService;
use App\Models\AffiliatedContentAccountService;

/**
 * Class AchievementController
 * @package App\Http\Controllers
 */
class AchievementController extends Controller
{ 
    /**
     * StudentController constructor.
     * @throws \Exception
     */
    public function __construct()
    {

        $this->sequencesCache = cache()->tags('connection_sequences_redis')->rememberForever('sequences_redis', function () {
            return CompanySequence::all();
        });
    }

    /**
     * Function for retrive the progress and performance for sequence
     * @param $accountService
     * @param $student_id
     * @param $sequence_id 
     * @return array
     */
    public static function retriveProgressSequence($accountService, $student_id, $sequence_id) 
    {
        
        $sequence = [];
        $momentCount = 0;
        $momentSectionCount = 4; 
    
        if ($accountService->rating_plan_type == 1) { //Si es plan por secuencia tiene acceso a todos los momentos
            $momentCount = 8;
        }
        else if ($accountService->rating_plan_type == 2 || $accountService->rating_plan_type == 3) { //Si es plan por momento o experiencia se valida el momento 
            //return count($affiliatedAccountService->affiliated_content_account_service->where('sequence_id', $sequence_id)->where('moment_id', $moment_id)) > 0;
            $momentCount = count($accountService->affiliated_content_account_service->where('sequence_id', $sequence_id));
            if($accountService->rating_plan_type == 3 )  {
                $momentSectionCount = 1;
            }
        }
       
        //calculando el progreso de la linea de avance de la secuencia  
        $advanceLine = AdvanceLine::where([
            ['affiliated_company_id',$student_id],
            ['affiliated_account_service_id',$accountService->id],
            ['sequence_id',$sequence_id]
        ])->get(); 

        //calculando los porcentajes de desempeño
        $ratings = Rating::where([
            ['affiliated_account_service_id',$accountService->id],
            ['student_id', $student_id],
            ['sequence_id',$sequence_id ] 
        ])->get();  

        $questions = Question::where('sequence_id',$sequence_id)
        ->select('sequence_id','experience_id', DB::raw('count(1) as total'))
        ->groupBy('sequence_id','experience_id')
        ->get(); 
       
        $sequence['progress'] = 100 * count($advanceLine) / ($momentCount*$momentSectionCount);
      
        if(count($ratings) >0 ) {
            $performance = $ratings->avg('weighted');
            $sequence['performance'] = number_format($performance,0);
        }
        else {
            $sequence['performance'] = -1;
        }

        if(  $sequence['progress'] == '100') {
             
            if($accountService->rating_plan_type == 1 || $accountService->rating_plan_type == 2 ) {
                if(count($ratings)>0 && count($questions) == count($ratings) ) {
                    $sequence['progress'] == '100';
                }
                else {
                    $sequence['progress'] == '79';
                }
            }
        }
        return [ 'sequence' => $sequence];
    }

    /**
     * Function for retrive the progress and performance for a moment
     * @param $accountService
     * @param $student_id
     * @param $sequence_id
     * @param $moment_id
     * @return array
     */
    public static function retriveProgressMoment($accountService, $student_id, $sequence_id, $moment_id, $moment_order) 
    {

        $momentCount = 0;
         
        if ($accountService->rating_plan_type == 1 || $accountService->rating_plan_type == 2) { //Si es plan por secuencia tiene acceso a todos los momentos
            $momentCount = 4;
        }
        else {
            $momentCount = 1;
        }

        //calculando el progreso de la linea de avance        
        $advanceLine = AdvanceLine::where([
            ['affiliated_company_id',$student_id],
            ['affiliated_account_service_id',$accountService->id],
            ['sequence_id',$sequence_id],
            ['moment_order',$moment_order]
        ])->orderBy('moment_order', 'ASC')->orderBy('moment_section_id', 'ASC')->get();
    
        $questions = Question::where([
            ['sequence_id',$sequence_id],
            ['moment_id',$moment_id]
        ])
        ->select('sequence_id','moment_id', DB::raw('count(1) as total'))
        ->groupBy('sequence_id','moment_id')
        ->get();

        //calculando los porcentajes de desempeño
        $ratings = Rating::where([
            ['affiliated_account_service_id',$accountService->id],
            ['student_id', $student_id],
            ['sequence_id',$sequence_id ],
            ['moment_id',$moment_id]
        ])->select('sequence_id','moment_id', DB::raw('count(1) as total'))
        ->groupBy('sequence_id','moment_id')
        ->get();
        
        $moment = [];
        $moment['progress'] = (count($advanceLine) / $momentCount) * 100;

        if($accountService->rating_plan_type != 3) {
            if( count($questions) > 0 && $moment['progress'] == 100) {
                if(count($ratings) == count($questions) ) {
                    $moment['progress'] = 100; 
                }
                else {
                    $moment['progress'] = 89;
                }
            }  
        } 
        
        $isAvailable = AffiliatedContentAccountService::with('sequence.moments')->where('sequence_id',$sequence_id)->where(function ($query)use($accountService){
           $query->where('affiliated_account_service_id',$accountService->id);
        })->where('moment_id', $moment_id)->get();
        
        
        $moment['isAvailable'] = $isAvailable != null && count($isAvailable) > 0;
       
        
        //calculando los porcentajes de desempeño
        $ratings = Rating::where([
            ['affiliated_account_service_id',$accountService->id],
            ['student_id',$student_id],
            ['sequence_id',$sequence_id],
            ['moment_id',$moment_id]
        ])->get();

        if(count($ratings)>0) {
            
            $performance = $ratings->avg('weighted');
            $moment['performance'] = number_format($performance,0); 
        } 
        else { 
            $moment['performance'] = -1;
        }
        $created_at = $advanceLine->max('updated_at');
        if($created_at != null) {
            $date =  Carbon::parse($created_at);
            $moment['lastAccessInMoment'] = $date->format("Y-m-d H:i");
        }
        else {
            $moment['lastAccessInMoment'] = null;
        }

        return [ 'moment' => $moment];
    }

    /**
     * Function for retrive the progress and performance for a moment section
     * @param $accountService
     * @param $student_id
     * @param $sequence_id
     * @param $moment_id
     * @param $moment_order
     * @param $section_id
     * @return array
     */
    public static function retriveProgressSection($accountService, $student_id, $sequence_id, $moment_id, $moment_order, $section_id) 
    {

        //calcula el Progreso según la línea de avance
        $advanceLine = AdvanceLine::where([
            ['affiliated_company_id',$student_id],
            ['affiliated_account_service_id',$accountService->id],
            ['sequence_id',$sequence_id],
            ['moment_order',$moment_order],
            ['moment_section_id',$section_id]
        ])->get();
        
        //calcula el desempeño según las evaluaciones
        $ratings = Rating::where([
            ['affiliated_account_service_id',$accountService->id],
            ['student_id',$student_id], 
            ['sequence_id',$sequence_id],
            ['moment_id',$moment_id],
            ['section',$section_id]
        ])->get();

        $questions = Question::where([
            ['sequence_id',$sequence_id],
            ['moment_id',$moment_order],
            ['section',($section_id)]
        ])
        ->select('sequence_id','moment_id','section', DB::raw('count(1) as total'))
        ->groupBy('sequence_id','moment_id','section')
        ->get();

        $section = [];
        $section['performance'] = null;
        $section['progress'] = 0;

        if( count($advanceLine) > 0) {
            if( count( $ratings ) == count($questions)) {
                $section['progress'] = 100;
            }
            else {
                $section['progress'] = 89;
            }
        }
 
        if(count($ratings) > 0 ) { 
            $section['performance'] = $ratings->avg('weighted') ? $ratings->avg('weighted') : 0;  
        }
        else if(count($questions) > 0){
            $section['performance'] = -1;
        }
        return [ 'section' => $section];
    }
    
    /**
     * Function for retrive answer in array question
     * @param $question
     * @param $answer_id answer from student
     * @return string
     */
    public static function retriveAnswer($question, $answer_id) 
    {   $options = json_decode($question['options'],true);
        if(isset($options) && count($options) >0) {
            foreach($options as $option) {
                if(isset($option['id'])) {
                    if($option['id'] == $answer_id) {
                        return strtoupper($option['id']) . '. ' . $option['option'];
                    }
                }
            }
        }
        return '';
        
    }

}
