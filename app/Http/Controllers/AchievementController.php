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
    public static function retriveProgressSequence($accountService, $student_id, $sequence) 
    { 
        
        $momentCount = 0;
        $momentSectionCount = 4; 
 
        if ($accountService->rating_plan_type == 1) { //Si es plan por secuencia tiene acceso a todos los momentos
            $momentCount = 8;
        }
        else if ($accountService->rating_plan_type == 2 || $accountService->rating_plan_type == 3) { //Si es plan por momento o experiencia se valida el momento 
            $momentCount = count($accountService->affiliated_content_account_service->where('sequence_id', $sequence->id));
            if($accountService->rating_plan_type == 3 )  {
                $momentSectionCount = 1;
            }
        }
       
        //calculando el progreso de la linea de avance de la secuencia  
        $advanceLine = AdvanceLine::where([
            ['affiliated_company_id',$student_id],
            ['affiliated_account_service_id',$accountService->id],
            ['sequence_id',$sequence->id]
        ])->get(); 
  
        $sequence['progress'] = 100 * count($advanceLine) / ($momentCount*$momentSectionCount);
        
        //calculando los porcentajes de desempeño
        $ratings = Rating::where([
            ['affiliated_account_service_id',$accountService->id],
            ['student_id', $student_id],
            ['sequence_id',$sequence->id ] 
        ])->get();  

        $questions = [];
        foreach($sequence->moments as $sequenceMoment) {
            foreach([1,2,3,4] as $section_id) {
                $section = json_decode($sequenceMoment['section_'.$section_id], true);
                if($accountService['rating_plan_type'] == 3) {
                    if($section['section']['type'] != 3) {
                    continue;
                    }
                }
                foreach([1,2,3,4,5] as $part_id) {
                    if(isset($section['part_'.$part_id]) && count($section['part_'.$part_id])>0) {
                        if(isset($section['part_'.$part_id]) && isset($section['part_'.$part_id]['elements'])) {
                            $elements = $section['part_'.$part_id]['elements'];
                            foreach($elements as $element) {
                                if($element['type'] =='evidence-element' && $element['questionEditType'] != 1 ) {
                                    $questions[$element['id']] = ['element'=>$element];
                                    
                                }
                            }
                        }
                    }
                }
            }    
        }
            
        if(count($questions)>0 ) {
            if(count($ratings) >0 ) {
                $performance = $ratings->avg('weighted');
                $sequence['performance'] = number_format($performance,0);
            }
            else {
                $sequence['performance'] = -1;
            }
        }
         
        if( $sequence['progress'] == '100') {
            if(count($questions) > 0 && count($questions) != count($ratings) ) {
                $sequence['progress'] = '79';
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
    public static function retriveProgressMoment($accountService, $student_id, $sequence_id, $sequenceMoment) 
    {
        $isAvailable = AffiliatedContentAccountService::with('sequence.moments')->where('sequence_id',$sequence_id)->where(function ($query)use($accountService){
            $query->where('affiliated_account_service_id',$accountService->id);
         })->where('moment_id', $sequenceMoment['id'])->get();
        
        
        if( $isAvailable == null || count($isAvailable) == 0) {
            $moment['isAvailable'] = false;
            return [ 'moment' => $sequenceMoment];
        }
       
        $sequenceMoment['isAvailable'] = true; 
       
        //Si es plan por secuencia o  momento tiene acceso a las 4 secciones del momento
        $momentSectionCount = null;
        if($accountService->rating_plan_type == 1 || $accountService->rating_plan_type == 2) {
            
            if($sequenceMoment['exclude_experience'] == 1) {
                $momentSectionCount = 3;
            }
            else { 
                $momentSectionCount = 4;
            }
        }
        else {
            $momentSectionCount = 1;
        }
        
        
        //calculando el progreso de la linea de avance        
        $advanceLine = AdvanceLine::where([
            ['affiliated_company_id',$student_id],
            ['affiliated_account_service_id',$accountService->id],
            ['sequence_id',$sequence_id],
            ['moment_order',$sequenceMoment->order]
        ])->orderBy('moment_order', 'ASC')->orderBy('moment_section_id', 'ASC')->get();
    
        $questions = [];

        $evidences = Rating::with('answers.question')
        ->where([
            ['sequence_id',$sequence_id],
            ['student_id',$student_id],
            ['affiliated_account_service_id',$accountService->id],
        ])->get();

        foreach([1,2,3,4] as $section_id) {
                
            $section = json_decode($sequenceMoment['section_'.$section_id], true);
            if($accountService['rating_plan_type'] == 3) {
                if($section['section']['type'] != 3) {
                continue;
                }
            }
            foreach([1,2,3,4,5] as $part_id) {
                if(isset($section['part_'.$part_id]) && count($section['part_'.$part_id])>0) {
                    if(isset($section['part_'.$part_id]) && isset($section['part_'.$part_id]['elements'])) {
                        $elements = $section['part_'.$part_id]['elements'];
                        foreach($elements as $element) {
                            if($element['type'] =='evidence-element' && $element['questionEditType'] != 1 ) {
                                $questions[$element['id']] = ['element'=>$element];
                                $questions[$element['id']]['evidences'] = $evidences->where('experience_id',$element['experience_id'])->first();
                                if($questions[$element['id']]['evidences']) {
                                    //  ($rating[$element['id']]['evidences']);
                                }
                            }
                        }
                    }
                }
            }
        }
   
        //calculando los porcentajes de desempeño
        $ratings = Rating::where([
            ['affiliated_account_service_id',$accountService->id],
            ['student_id',$student_id],
            ['sequence_id',$sequence_id],
            ['moment_id',$sequenceMoment->id]
        ])->get();  
            
        $sequenceMoment['progress'] = (count($advanceLine) / $momentSectionCount) * 100;
        if($sequenceMoment['progress'] > 100) {
            $sequenceMoment['progress'] = 100;
        }
 
        if( count($questions) > 0 && $sequenceMoment['progress'] == 100) {
            if(count($ratings) == count($questions) ) {
                $sequenceMoment['progress'] = 100; 
            }
            else {
                $sequenceMoment['progress'] = 89;
            }
        }  
        
        if( count($questions) > 0) {
            if(count($ratings)>0) { 
                $performance = $ratings->avg('weighted');
                $sequenceMoment['performance'] = number_format($performance,0); 
            } 
            else { 
                $sequenceMoment['performance'] = -1;
            } 
        }

        $created_at = $advanceLine->max('updated_at');
        if($created_at != null) {
            $date =  Carbon::parse($created_at);
            $sequenceMoment['lastAccessInMoment'] = $date->format("Y-m-d H:i");
        }
        else {
            $sequenceMoment['lastAccessInMoment'] = null;
        }
        $sequenceMoment['questions'] = $questions;
        return [ 'moment' => $sequenceMoment];
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
    public static function retriveProgressSection($accountService, $student_id, $sequence_id, $sequenceMoment, $section_id) 
    {

        //calcula el Progreso según la línea de avance
        $advanceLine = AdvanceLine::where([
            ['affiliated_company_id',$student_id],
            ['affiliated_account_service_id',$accountService->id],
            ['sequence_id',$sequence_id],
            ['moment_order',$sequenceMoment->order],
            ['moment_section_id',$section_id]
        ])->get();
      
        //calcula el desempeño según las evaluaciones
        $ratings = Rating::where([
            ['affiliated_account_service_id',$accountService->id],
            ['student_id',$student_id], 
            ['sequence_id',$sequence_id],
            ['moment_id',$sequenceMoment->id],
            ['section',$section_id]
        ])->get(); 
         
        $section = json_decode($sequenceMoment['section_'.$section_id], true);
        
        $questions = [];
        foreach([1,2,3,4,5] as $part_id) {
            if(isset($section['part_'.$part_id]) && count($section['part_'.$part_id])>0) {
                if(isset($section['part_'.$part_id]) && isset($section['part_'.$part_id]['elements'])) {
                    $elements = $section['part_'.$part_id]['elements'];
                    foreach($elements as $element) {
                        if($element['type'] =='evidence-element' && $element['questionEditType'] != 1 ) {
                            $questions[$element['id']] = ['element'=>$element];
                            
                        }
                    }
                }
            }
        } 

        
        $section = [];
        $section['progress'] = -1;
    
        if( count($advanceLine) > 0 ) {
            $section['progress'] = 100;
            if( count($questions) > 0 && count( $ratings ) != count($questions)) {
                $section['progress'] = 89;
            }
        } 

       // 
        if(count($questions)>0) {
            
            if( count( $ratings ) > 0) {
                $section['performance'] = $ratings->avg('weighted') ? $ratings->avg('weighted') : 0;
            }
            else{
                $section['performance'] = -1;
            }
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
