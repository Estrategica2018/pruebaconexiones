<?php

namespace App\Http\Controllers;

use App\Mail\PlanCompletion;
use App\Mail\SendSuccessfulPaymentNotification;
use App\Models\AdvanceLine;
use App\Models\AffiliatedAccountService;
use App\Models\AffiliatedCompanyRole;
use App\Models\AffiliatedContentAccountService;
use App\Models\AfiliadoEmpresa;
use App\Models\AfiliadoEmpresaRoles;
use App\Models\ConectionAffiliatedStudents;
use App\Models\Answer;
use App\Models\Rating;
use App\Models\Question;
use App\Models\Companies;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Models\CompanySequence;
use App\Models\SequenceMoment;
use App\Models\Achievement;
use Illuminate\Support\Facades\Mail;

/**
 * Class StudentController
 * @package App\Http\Controllers
 */
class StudentController extends Controller
{
    /**
     * @var
     */
    private $sequencesCache;

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
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        if ($request->user('afiliadoempresa')->url_image == null) {
            return view('roles.student.avatar');
        } else {

            $age = '';
            if (isset($request->user('afiliadoempresa')->birthday) && $request->user('afiliadoempresa')->birthday > 0) {
                $age = Carbon::now()->diffInYears(Carbon::parse($request->user('afiliadoempresa')->birthday));
            }
            return view('roles.student.profile', ['student' => $request->user('afiliadoempresa'), 'age' => $age]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_available_sequences(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        return view('roles.student.available_sequences');
    }

    public function show_available_experiences(Request $request, $empresa, $sequence_id, $account_service_id)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        return view('roles.student.available_experiences',['sequence_id'=>$sequence_id,'account_service_id'=>$account_service_id]);
    }
    

    /**
     * @param Request $request
     * @param $empresa
     * @param int $company_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_achievements(Request $request, $empresa, $company_id = 1)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        $student = $request->user('afiliadoempresa');
        $accountServices = $this->get_available_sequences($request, $empresa, $company_id, null, false);
        
        $countSequences = 0;
        $sequence_id_tmp = 0;
        $accountServiceTmp = null;
        $accountServicesList = [];
        foreach($accountServices as $accountService) {
            foreach($accountService->affiliated_content_account_service as $seq) {
                if($sequence_id_tmp != $seq->sequence_id) {
                    if($accountService->is_active == 1) {
                      $countSequences = $countSequences + 1;
                    }
                    $accountServiceTmp = clone $accountService;
                    $accountServiceTmp->sequence = clone $seq->sequence;
                    array_push($accountServicesList,$accountServiceTmp);
                    $sequence_id_tmp = $seq->sequence_id;
                    
                    $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($accountService, $student->id, $accountServiceTmp->sequence);
                    $accountServiceTmp->sequence['progress'] = $result['sequence']['progress'];
                    if(isset($result['sequence']['performance'])) {
                        $accountServiceTmp->sequence['performance'] = $result['sequence']['performance']; 
                    }
                }
            }
        }
        
        $firstAccess = $student->first_last_access()['first'];
        $lastAccess = $student->first_last_access()['last'];
  
        return view('roles.student.achievements.index', ['accountServices'=> $accountServicesList, 'student' => $student, 'countSequences' => $countSequences, 'firstAccess' => $firstAccess, 'lastAccess' => $lastAccess]);
    }

    /**
     * @param Request $request
     * @param $empresa
     * @param int $affiliated_account_service_id
     * @param int $sequence_id
     * @param int $company_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_achievements_sequence(Request $request, $empresa, $affiliated_account_service_id, $sequence_id, $company_id = 1)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        $student = $request->user('afiliadoempresa');
        $accountServices = $this->get_available_sequences($request, $empresa, $company_id, $affiliated_account_service_id,false);
        $accountAllServices = $this->get_available_sequences($request, $empresa, $company_id);
        
        if( count($accountServices) < 1) {
            return $this->finishValidate('No tiene permiso para acceder a este módulo');
        }
       
        $countSequences = count($accountAllServices);
        $firstAccess = $student->first_last_access()['first'];
        $lastAccess = $student->first_last_access()['last'];
        $accountService = $accountServices[0];
        foreach($accountService->affiliated_content_account_service as $acc) {
            if($acc->sequence->id == $sequence_id) {
              $sequence = clone $acc->sequence;
            }
        }
        
        if($sequence == null ) {
            return $this->finishValidate('Plan de accceso o secuencia no válidas');
        }
    
 
        $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($accountService, $student->id, $sequence);
        $sequence['progress'] = $result['sequence']['progress'];
        $sequence['performance'] = $result['sequence']['performance'];
  

        $moments = [];
        foreach($sequence->moments as $moment) {
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressMoment($accountService, $student->id, $sequence->id, $moment);
            $moment['isAvailable'] = $result['moment']['isAvailable'];
            if($moment['isAvailable']) {
                 $moment['progress'] = $result['moment']['progress'];
                $moment['performance'] = $result['moment']['performance'];
                $moment['isAvailable'] = $result['moment']['isAvailable'];
            }
           
            array_push($moments,$moment);
        }
       
        return view('roles.student.achievements.sequence', ['rating_plan_type'=>$accountService->rating_plan_type,'student' => $student, 'countSequences' => $countSequences, 'firstAccess' => $firstAccess, 'lastAccess' => $lastAccess, 'sequence'=>$sequence, 'moments' => $moments, 'affiliated_account_service_id' => $accountService->id] );
    }

    /**   
     * @param Request $request
     * @param $empresa
     * @param int $affiliated_account_service_id
     * @param int $sequence_id
     * @param int $company_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_achievements_moment(Request $request, $empresa, $affiliated_account_service_id, $sequence_id, $company_id = 1)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        $student = $request->user('afiliadoempresa');
       
        $accountServices = $this->get_available_sequences($request, $empresa, $company_id, $affiliated_account_service_id, true);
        $accountAllServices = $this->get_available_sequences($request, $empresa, $company_id);
        $countSequences = count($accountAllServices);
        
        if( count($accountServices) < 1) {
            return $this->finishValidate('No tiene permiso para acceder a este módulo');
        }
        
        $firstAccess = $student->first_last_access()['first'];
        $lastAccess = $student->first_last_access()['last'];
        
        $accountService = $accountServices[0];
        foreach($accountService->affiliated_content_account_service as $acc) {
            if($acc->sequence->id == $sequence_id) {
              $sequence = clone $acc->sequence;
            }
        }
        
        if($sequence == null ) {
            return $this->finishValidate('Plan de accceso o secuencia no válidas');
        }
        
        //$sequence = CompanySequence::with('moments')->find($sequence_id);
        $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($accountService, $student->id, $sequence);
        $sequence['progress'] = $result['sequence']['progress'];
        $sequence['performance'] = $result['sequence']['performance'];
         
        
        $moments = [];
        foreach($sequence->moments as $sequenceMoment) {
            
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressMoment($accountService, $student->id, $sequence->id, $sequenceMoment);
            $sequenceMoment['isAvailable'] = $result['moment']['isAvailable'];
            if($sequenceMoment['isAvailable']) {
                $sequenceMoment['progress'] = $result['moment']['progress'];
                $sequenceMoment['performance'] = $result['moment']['performance'];
                $sequenceMoment['lastAccessInMoment'] = $result['moment']['lastAccessInMoment'];
                
            }
 
            $section_1 = json_decode($sequenceMoment->section_1,true);
            $section_2 = json_decode($sequenceMoment->section_2,true);
            $section_3 = json_decode($sequenceMoment->section_3,true);
            $section_4 = json_decode($sequenceMoment->section_4,true);
            $sections = [
                'section_1' => ['name' => $section_1['section']['name'],'title' => isset($section_1['title']) ? $section_1['title'] : '', 'section' => $section_1],
                'section_2' => ['name' => $section_2['section']['name'],'title' => isset($section_2['title']) ? $section_2['title'] : '', 'section' => $section_2],
                'section_3' => ['name' => $section_3['section']['name'],'title' => isset($section_3['title']) ? $section_3['title'] : '', 'section' => $section_3],
                'section_4' => ['name' => $section_4['section']['name'],'title' => isset($section_4['title']) ? $section_4['title'] : '', 'section' => $section_4],
            ]; 
            $section_id=1; 
            foreach($sections as &$section) {
                $result = app('App\Http\Controllers\AchievementController')->retriveProgressSection($accountService, $student->id, $sequence->id, $sequenceMoment, $section_id);
                $section['progress'] = $result['section']['progress'];
                if(isset($result['section']['performance'])) {
                    $section['performance'] = $result['section']['performance'];
                }
                
                $section['isShow'] = true;
                if($sequenceMoment->exclude_experience == 1) {
                    if($section['section']['section']['type'] == 3) {
                        $section['isShow'] = false;
                    }
                }
                
                $section_id ++;
                if ($accountService->rating_plan_type == 1 || $accountService->rating_plan_type == 2) { //Si es plan por secuencia o momento tiene acceso a todas las secciones
                    $section['isAvailable'] = true;
                }
                else if ($accountService->rating_plan_type == 3  ) { //Si es plan por experiencia se valida la seccion experiencia cientifica
                    if($section['section']['section']['type'] == 3) {
                        $section['isAvailable'] = true;
                    }
                    else {
                        $section['isAvailable'] = false;
                    }
                }
            } 

            $sequenceMoment['sections'] = $sections;
            array_push($moments,$sequenceMoment);
        }
        
        return view('roles.student.achievements.moment', ['rating_plan_type'=>$accountService->rating_plan_type,'student' => $student, 'countSequences' => $countSequences, 'firstAccess' => $firstAccess, 'lastAccess' => $lastAccess, 'sequence'=>$sequence, 'moments' => $moments, 'affiliated_account_service_id' => $accountService->id]  );
    }

    /**   
     * @param Request $request
     * @param $empresa
     * @param int $affiliated_account_service_id
     * @param int $sequence_id
     * @param int $company_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_achievements_question(Request $request, $empresa, $affiliated_account_service_id, $sequence_id, $company_id = 1)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        $student = $request->user('afiliadoempresa');
        $accountServices = $this->get_available_sequences($request, $empresa, $company_id, $affiliated_account_service_id,false);
        $accountAllServices = $this->get_available_sequences($request, $empresa, $company_id);

        if( count($accountServices) < 1) {
            return $this->finishValidate('No tiene permiso para acceder a este módulo');
        }
        
        $countSequences = count($accountAllServices);
        $firstAccess = $student->first_last_access()['first'];
        $lastAccess = $student->first_last_access()['last'];  

        $accountService = $accountServices[0];
        foreach($accountService->affiliated_content_account_service as $acc) {
            if($acc->sequence->id == $sequence_id) {
              $sequence = clone $acc->sequence;
            }
        }
        
        if($sequence == null ) {
            return $this->finishValidate('Plan de accceso o secuencia no válidas');
        }
        
        $sequence = CompanySequence::with('moments')->find($sequence_id);
        $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($accountService, $student->id, $sequence);
        $sequence['progress'] = $result['sequence']['progress'];
        $sequence['performance'] = $result['sequence']['performance'];
        
        $moments = []; 
        foreach($sequence->moments as $sequenceMoment) {
           
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressMoment($accountService, $student->id, $sequence->id, $sequenceMoment);
            $sequenceMoment['isAvailable'] = $result['moment']['isAvailable']; 
            if($sequenceMoment['isAvailable'] ) {
                $sequenceMoment['progress'] = $result['moment']['progress'];
                $sequenceMoment['performance'] = $result['moment']['performance'];
                $sequenceMoment['lastAccessInMoment'] = $result['moment']['lastAccessInMoment'];
                $sequenceMoment['ratings'] = $result['moment']['questions'];
            }
            else {
                $sequenceMoment['ratings'] = [];
              
            }
            
            array_push($moments,$sequenceMoment);
        }

        
        return view('roles.student.achievements.questions', ['rating_plan_type'=>$accountService->rating_plan_type,'student' => $student, 'countSequences' => $countSequences, 'firstAccess' => $firstAccess, 'lastAccess' => $lastAccess, 'sequence'=>$sequence, 'moments' => $moments, 'affiliated_account_service_id' => $affiliated_account_service_id]  );
    }
 
    /**
     * @param Request $request
     * @param $empresa
     * @param $sequence_id
     * @param $account_service_id
     * @param int $part_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_sequences_section_1(Request $request, $empresa, $sequence_id, $account_service_id, $part_id = 1)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        
        $affiliatedAccountService = 
            AffiliatedAccountService::with('affiliated_content_account_service')->
            where('init_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->find($account_service_id);
        
        $validate = $this->validation_access_sequence_content($empresa,$account_service_id,$sequence_id);
        
        if($validate) {
             return $validate;
        }
        
        //$sequence = CompanySequence::where('id',$sequence_id)->get()->first();
        $sequence = $this->sequencesCache->where('id', $sequence_id)->first();
        $section_part_id = 1;
        if ($sequence->section_1) {
            $sections = json_decode($sequence->section_1, true); 
            $section = $sections['part_' . $part_id];
            $buttonBack = 'none';
            if ($part_id > 1) {
                $buttonBack = route('student.sequences_section_1', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => ($part_id - 1)]);
            } 
            if (isset(json_decode($sequence->section_1, true)['part_' . ($part_id + 1)])) {
                
                $buttonNext = route('student.sequences_section_1', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => ($part_id + 1)]);
            } else {
                $buttonNext = route('student.sequences_section_2', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id]);
            }
    
            $data = array_merge(['empresa'=>$empresa,'sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext,'sectionParts'=>$sections, 'part_id'=>$part_id,'section_part_id'=>$section_part_id, 'rating_plan_type' => $affiliatedAccountService->rating_plan_type], $section);
            return view('roles.student.content_sequence_section', $data)->with('account_service_id', $account_service_id)->with('sequence_id', $sequence_id);
        }
    }

    /**
     * @param Request $request
     * @param $empresa
     * @param $sequence_id
     * @param $account_service_id
     * @param int $part_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_sequences_section_2(Request $request, $empresa, $sequence_id, $account_service_id, $part_id = 1)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        
        $affiliatedAccountService = 
            AffiliatedAccountService::with('affiliated_content_account_service')->
            where('init_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->find($account_service_id);
        
        $validate = $this->validation_access_sequence_content($empresa,$account_service_id,$sequence_id);
        if($validate) {
                return $validate;
        }
        
        //$sequence = CompanySequence::where('id',$sequence_id)->get()->first();
        $section_part_id = 2;
        $sequence = $this->sequencesCache->where('id', $sequence_id)->first();
        if ($sequence->section_2) {
            $sections = json_decode($sequence->section_2, true);
            $section = $sections['part_' . $part_id];
            $buttonBack = 'none';
            if ($part_id > 1) {
                $buttonBack = route('student.sequences_section_2', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => ($part_id - 1)]);
            } else {
                $section_1 = json_decode($sequence->section_1, true);
                $last_part_id = 1;
                foreach ($section_1 as $key => $value) {
                    if (strpos('_' . $key, 'part_') != false) {
                        $num = (int)str_replace('part_', '', $key);
                        if ($num > $last_part_id && $value) {
                            $last_part_id = $num;
                        }
                    }
                }
                $buttonBack = route('student.sequences_section_1', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => $last_part_id]);
            }
            if (isset(json_decode($sequence->section_2, true)['part_' . ($part_id + 1)])) {
                $buttonNext = route('student.sequences_section_2', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => ($part_id + 1)]);
            } else {
                $buttonNext = route('student.sequences_section_3', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id]);
            }
            $data = array_merge(['empresa'=>$empresa,'sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext,'sectionParts'=>$sections, 'part_id'=>$part_id, 'section_part_id'=>$section_part_id, 'rating_plan_type' => $affiliatedAccountService->rating_plan_type], $section);
            return view('roles.student.content_sequence_section', $data)->with('account_service_id', $account_service_id)->with('sequence_id', $sequence_id);
        }
    }

    /**
     * @param Request $request
     * @param $empresa
     * @param $sequence_id
     * @param $account_service_id
     * @param int $part_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_sequences_section_3(Request $request, $empresa, $sequence_id, $account_service_id, $part_id = 1)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        
        $affiliatedAccountService = 
            AffiliatedAccountService::with('affiliated_content_account_service')->
            where('init_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->find($account_service_id);

        //$sequence = CompanySequence::where('id',$sequence_id)->get()->first();
        $section_part_id = 3;
        
        $validate = $this->validation_access_sequence_content($empresa,$account_service_id,$sequence_id);
        if($validate) {
                return $validate;
        }
        
        $sequence = $this->sequencesCache->where('id', $sequence_id)->first();
        if ($sequence->section_3) {
            $sections = json_decode($sequence->section_3, true);
            $section = $sections['part_' . $part_id];
            $buttonBack = 'none';
            if ($part_id > 1) {
                $buttonBack = route('student.sequences_section_3', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => ($part_id - 1)]);
            } else {
                $section_2 = json_decode($sequence->section_2, true);
                $last_part_id = 1;
                foreach ($section_2 as $key => $value) {
                    if (strpos('_' . $key, 'part_') != false) {
                        $num = (int)str_replace('part_', '', $key);
                        if ($num > $last_part_id && $value) {
                            $last_part_id = $num;
                        }
                    }
                }
                $buttonBack = route('student.sequences_section_2', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => $last_part_id]);
            }
            if (isset(json_decode($sequence->section_3, true)['part_' . ($part_id + 1)])) {
                $buttonNext = route('student.sequences_section_3', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => ($part_id + 1)]);
            } else {
                $buttonNext = route('student.sequences_section_4', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id]);
            }
            $data = array_merge(['empresa'=>$empresa,'sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext,'sectionParts'=>$sections, 'part_id'=>$part_id, 'section_part_id'=>$section_part_id, 'rating_plan_type' => $affiliatedAccountService->rating_plan_type], $section);
            return view('roles.student.content_sequence_section', $data)->with('account_service_id', $account_service_id)->with('sequence_id', $sequence_id);
        }

    }

    /**
     * @param Request $request
     * @param $empresa
     * @param $sequence_id
     * @param $account_service_id
     * @param int $part_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_sequences_section_4(Request $request, $empresa, $sequence_id, $account_service_id, $part_id = 1)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        
        $affiliatedAccountService = 
            AffiliatedAccountService::with('affiliated_content_account_service')->
            where('init_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->find($account_service_id);
        
        $validate = $this->validation_access_sequence_content($empresa,$account_service_id,$sequence_id);
        if($validate) {
                return $validate;
        }
        
        $section_part_id = 4;
        
        $sequence = $this->sequencesCache->where('id', $sequence_id)->first();
        if ($sequence->section_4) {
            $sections = json_decode($sequence->section_4, true);
            $section = $sections['part_' . $part_id];
            $buttonBack = 'none';
            if ($part_id > 1) {
                $buttonBack = route('student.sequences_section_4', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => ($part_id - 1)]);
            } else {
                $section_3 = json_decode($sequence->section_3, true);
                $last_part_id = 1;
                foreach ($section_3 as $key => $value) {
                    if (strpos('_' . $key, 'part_') != false) {
                        $num = (int)str_replace('part_', '', $key);
                        if ($num > $last_part_id && $value) {
                            $last_part_id = $num;
                        }
                    }
                }
                $buttonBack = route('student.sequences_section_3', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => $last_part_id]);
            }
            if (isset(json_decode($sequence->section_4, true)['part_' . ($part_id + 1)])) {
                $buttonNext = route('student.sequences_section_4', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id, 'part_id' => ($part_id + 1)]);
            } else {
                foreach($sequence->moments as $next_moment) {
                    $has_moment = $this->validation_access_moment($account_service_id, $sequence->id, $next_moment->id);
                    if($has_moment){
                        $buttonNext = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                        'moment_id' => $next_moment->id,
                        'order_moment_id' => $next_moment->order,
                        'sequence_id' => $sequence_id]);
                        break;
                    }
                }
            }
            $data = array_merge(['empresa'=>$empresa,'sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext,'sectionParts'=>$sections, 'part_id'=>$part_id, 'section_part_id'=>$section_part_id, 'rating_plan_type' => $affiliatedAccountService->rating_plan_type], $section);
             
            return view('roles.student.content_sequence_section', $data)->with('account_service_id', $account_service_id)->with('sequence_id', $sequence_id);
        }

    }

    /**
     * @param Request $request
     * @param $empresa
     * @param $sequence_id
     * @param $moment_id
     * @param $section_id
     * @param $account_service_id
     * @param $order_moment_id
     * @param int $part_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_moment_section(Request $request, $empresa, $account_service_id, $sequence_id, $moment_id, $order_moment_id, $section_id = null, $part_id = 1)
    {
        $affiliatedAccountService = 
            AffiliatedAccountService::with('affiliated_content_account_service')->
            where('init_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->find($account_service_id);
            
        if($affiliatedAccountService == null) {
            return $this->finishValidate('No tiene permisos para acceder a este módulo');
        }
        
        $moment = SequenceMoment::with('sequence')
            ->where([
                ['sequence_company_id', $sequence_id],
                ['id', $moment_id],
                ['order', $order_moment_id]
            ])
            ->first();
        
        if($moment == null) {
            return $this->finishValidate('Página no encontrada');
        }

        if($section_id === null) { 
            if($affiliatedAccountService->rating_plan_type == 3){
                $section_id = $this->sectionExperiencesInMoment($moment);
            }
            else { 
                if($moment->exclude_experience == 1) {
                    $section_id = $this->sectionExperiencesInMoment($moment);
                    $section_id = $section_id === 1 ||  $section_id == null ? 2 : 1;
                }
                else {
                    $section_id = 1;
                }
            }
        }

        $section = json_decode($moment['section_'.$section_id],true);
        
        if (!(isset($section) && $section != null && $section['section'] != null))  {
            return $this->finishValidate('Página no encontrada');
        }
        $section_type = $section['section']['type'];
        

        $validate = $this->validation_access_sequence_content($empresa,$account_service_id, $sequence_id, $moment_id, $section_type);

        if($validate) {
            return $validate;
        }
        
        $sequence = CompanySequence::where('id', $sequence_id)->get()->first();
        
        //Notificación al 100% de finalización de la guía
        $student = auth('afiliadoempresa')->user();
        $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($affiliatedAccountService, $student->id, $sequence);
        
        $mbControlSendEmail = false;
        if($result['sequence']['progress'] < 100) {
            $mbControlSendEmail = true;
        }
        
        $item = AdvanceLine::updateOrCreate(
            [
                'affiliated_account_service_id' => $account_service_id,
                'affiliated_company_id' => auth('afiliadoempresa')->user()->id,
                'sequence_id' => $sequence_id,
                'moment_order' => $order_moment_id,
                'moment_section_id' => $section_id
            ],
            [
                'created_at' => ''
            ]
        );
        $item->save();

        if($mbControlSendEmail) {
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($affiliatedAccountService, $student->id, $sequence);
            if($result['sequence']['progress'] === 100) {
                Mail::to($student->emailForContact())->send(
                    new PlanCompletion($student->nameFamiliar(),$student,$sequence,$affiliatedAccountService->rating_plan));
            }
        }

        if ($moment['section_' . $section_id]) {
            $section = json_decode($moment['section_' . $section_id], true);
            $section_1 = json_decode($moment->section_1, true);
            $section_2 = json_decode($moment->section_2, true);
            $section_3 = json_decode($moment->section_3, true);
            $section_4 = json_decode($moment->section_4, true);
            $part = json_decode($moment['section_' . $section_id], true)['part_' . $part_id];
            
            $buttonBack = 'none';
            if ($part_id > 1) {
                if( !isset($part['elements']) || count($part['elements']) <= 0 ) {
                    return $this->finishValidate('Página no encontrada');    
                }
                $buttonBack = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                    'sequence_id' => $sequence_id,
                    'moment_id' => $moment_id,
                    'section_id' => $section_id,
                    'account_service_id' => $account_service_id,
                    'order_moment_id' => $order_moment_id,
                    'part_id' => ($part_id - 1)]);
            } else {
                $back_section_id = $section_id - 1;
            
                if($moment->exclude_experience == 1) {
                    
                    if($back_section_id == $this->sectionExperiencesInMoment($moment)) {
                        $back_section_id --;
                    }
                }
                    
                if($back_section_id >= 1 && $affiliatedAccountService->rating_plan_type != 3) {
                    $last_part_id = $this->lastPartInSection($moment, $back_section_id);
                    $buttonBack = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                        'sequence_id' => $sequence_id,
                        'moment_id' => $moment_id,
                        'section_id' => $back_section_id ,
                        'account_service_id' => $account_service_id,
                        'order_moment_id' => $order_moment_id,
                        'part_id' => $last_part_id]); 
                }
                else {
                    if($order_moment_id == 1) {
                        $buttonBack = route('student.sequences_section_4', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id]);
                    }
                    else {
                        
                        $has_moment = false;
                        if($affiliatedAccountService->rating_plan_type == 3){
                            for($i = $order_moment_id - 1; $i >= 1; $i--) {
                                $last_moment = $sequence->moments[$i-1];
                                $has_moment = $this->validation_access_moment($account_service_id, $sequence_id, $last_moment->id);
                                if($has_moment){ 
                                    break;
                                }
                            }
                        }else{
                            for($i = $order_moment_id - 1; $i >= 1; $i--) {
                                $last_moment = $sequence->moments[$i-1];
                                $has_moment = $this->validation_access_moment($account_service_id, $sequence_id, $last_moment->id);
                                if($has_moment){ 
                                    break;
                                }
                            }
                        }
                        if($has_moment){ 
                             
                            $last_section =  $affiliatedAccountService->rating_plan_type == 3  ? 
                                $this->sectionExperiencesInMoment($last_moment) : $last_section = 4;
                            
                            if($moment->exclude_experience == 1 ) {
                                if($this->sectionExperiencesInMoment($last_moment) == $last_section) {
                                    $last_section --;
                                }
                            }
                              
                            $last_part_id = $this->lastPartInSection($last_moment, $last_section);
                                 
                            $buttonBack = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                                'sequence_id' => $sequence_id,
                                'moment_id' => $last_moment->id,
                                'order_moment_id' => $last_moment->order,
                                'section_id' => $last_section,
                                'part_id' => $last_part_id]);
                        }

                    }
                }
            }
            $buttonNext = 'none';
            
            if (isset($section['part_' . ($part_id + 1)]) && isset($section['part_' . ($part_id + 1)]['elements']) && count($section['part_' . ($part_id + 1)]['elements'])>0 ) {
                $buttonNext = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                    'sequence_id' => $sequence_id,
                    'moment_id' => $moment_id,
                    'section_id' => $section_id,
                    'account_service_id' => $account_service_id,
                    'order_moment_id' => $order_moment_id,
                    'part_id' => $part_id + 1]);
            } else {
                if($section_id < 4) {
                    
                    $affiliatedAccountService = AffiliatedAccountService::with('affiliated_content_account_service')->
                    where('init_date', '<=', Carbon::now())
                        ->where('end_date', '>=', Carbon::now())->find($account_service_id);
                    if($affiliatedAccountService->rating_plan_type == 3){
                        $buttonNext = 'none';
                        for($i = $order_moment_id + 1; $i <= count($sequence->moments); $i++) {
                            $next_moment = $sequence->moments[$i-1];
                            $has_moment = $this->validation_access_moment($account_service_id, $sequence_id, $next_moment->id);

                            if($has_moment){

                                $nex_section_id = $this->sectionExperiencesInMoment($next_moment); 

                                $buttonNext = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                                    'sequence_id' => $sequence_id,
                                    'moment_id' => $next_moment->id,
                                    'order_moment_id' => $next_moment->order,
                                    'section_id' => $nex_section_id,
                                    'part_id' => 1]);
                                break;
                            }

                        }
                    }else{
                        $nex_section_id = $section_id + 1;
                        if($moment->exclude_experience == 1) {
                            $experience_section_id = $this->sectionExperiencesInMoment($moment); 
                            if($experience_section_id == $nex_section_id ) {
                                $nex_section_id = $nex_section_id + 1;
                            }
                        }
                        
                        $buttonNext = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                            'sequence_id' => $sequence_id,
                            'moment_id' => $moment_id,
                            'section_id' => $nex_section_id,
                            'account_service_id' => $account_service_id,
                            'order_moment_id' => $order_moment_id,
                            'part_id' => 1]);
                    }
                }
                else {
                    for($i = $order_moment_id + 1; $i <= count($sequence->moments); $i++) {
                        $next_moment = $sequence->moments[$i-1];
                        $has_moment = $this->validation_access_moment($account_service_id, $sequence_id, $next_moment->id);
                        if($has_moment){
                             
                            $buttonNext = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                            'sequence_id' => $sequence_id,
                            'moment_id' => $next_moment->id,
                            'order_moment_id' => $next_moment->order]);
                            break;
                        }
                    }
                }
            }
            
            
        
            $data = array_merge(['empresa'=>$empresa,'sequence' => $moment->sequence, 'sequence_id' => $sequence_id,'section_id'=>$section_id,'part_id' => $part_id, 'rating_plan_type' => $affiliatedAccountService->rating_plan_type,
                'section_type'=>$section_type,
				'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext, 'moment' => $moment, 'sections' => [$section_1, $section_2, $section_3, $section_4]], $section, $part);
            return view('roles.student.content_sequence_section', $data)->with('account_service_id', $account_service_id)->with('order_moment_id', $order_moment_id);
        }
    }

    /**
     * @param Request $request
     * @param $empresa
     * @param $company_id
     * @param $accountService_id
     * @return AffiliatedContentAccountService[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get_available_sequences(Request $request, $empresa, $company_id, $accountService_id = null, $onlyAvailable= true)
    {

        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        
        $tutor_id = ConectionAffiliatedStudents::select('id', 'tutor_company_id')
            ->whereHas('student_family', function ($query) use ($request, $company_id) {
                $query->where([
                    ['affiliated_company_id', $request->user('afiliadoempresa')->id],
                    ['company_id', $company_id],
                    ['rol_id', 1]
                ]);
            })->first();  

        /*$accountServices = AffiliatedAccountService::
        with('rating_plan','affiliated_content_account_service.sequence')
        ->whereHas('company_affiliated', function ($query) use ($tutor_id) {
            $query->where('id', $tutor_id->tutor_company_id);
        })
        */

        $accountServices = AffiliatedAccountService::
            with('rating_plan','affiliated_content_account_service.sequence')
            ->whereHas('company_affiliated', function ($query) use ($tutor_id) {
                $query->where('id', $tutor_id->tutor_company_id);
            })
            ->select(DB::raw('*,(CASE WHEN init_date <= CURRENT_DATE and end_date >= CURRENT_DATE  THEN 1 ELSE 0 END) AS is_active'))
            ->orderBy('end_date', 'desc'); 
     
        
        if(  $accountService_id != null) {
            $accountServices = $accountServices->where('id',$accountService_id);
        }
 
        if($onlyAvailable) {
            $accountServices = $accountServices->where([
                ['init_date', '<=', Carbon::today()],
                ['end_date', '>=', Carbon::today()]
            ]);
        }

        return $accountServices->orderBy('created_at', 'desc')->get();
        
        /*if($groupBy) {
            return  AffiliatedAccountService::
                with('rating_plan','affiliated_content_account_service.sequence')
                ->whereHas('company_affiliated', function ($query) use ($tutor_id) {
                    $query->where('id', $tutor_id->tutor_company_id);
                })
                ->where([
                    ['init_date', '<=', Carbon::now()],
                    ['end_date', '>=', Carbon::now()]
                ])->get();
        }
        else {
            return AffiliatedContentAccountService::with('sequence')->whereIn('affiliated_account_service_id', $ids)->get();
        }*/ 

    }

    public function get_available_experiences(Request $request,$company_id,$sequence_id,$account_service_id){


        $tutor_id = ConectionAffiliatedStudents::select('id', 'tutor_company_id')
            ->whereHas('student_family', function ($query) use ($request, $company_id) {
                $query->where([
                    ['affiliated_company_id', $request->user('afiliadoempresa')->id],
                    ['company_id', $company_id],
                    ['rol_id', 1]
                ]);
            })->first();

        $ids = AffiliatedAccountService::
        with('rating_plan')->whereHas('company_affiliated', function ($query) use ($tutor_id) {
            $query->where('id', $tutor_id->tutor_company_id);
        })->where([
            //['id',$account_service_id],
            ['init_date', '<=', Carbon::now()],
            ['end_date', '>=', Carbon::now()]
        ])->pluck('id');

        $sequences = AffiliatedContentAccountService::with('sequence.moments')->where('sequence_id',$sequence_id)->where(function ($query)use($ids){
           $query->whereIn('affiliated_account_service_id',$ids);
        })->get();
        $datas = [];
        $companySequence = CompanySequence::find($sequences[0]->sequence_id);
        $datas ['sequence_id'] = $companySequence->id;
        $datas ['sequence_name'] = $companySequence->name;
        $datas ['sequence_url_image'] = $companySequence->url_image;
        $datas ['moments'] = [];
        foreach($sequences as $sequence) {
            $moment = SequenceMoment::find($sequence->moment_id);
            $data['parts'] = [];
            $part = [];
            $dataVideo = [];
            $flag = false;
            foreach([1,2,3,4] as $section_id) {
                    $section = json_decode($moment['section_'.$section_id], true);
                    if($section['section']['type'] == 3){
                        foreach([1,2,3,4,5] as $part_id) {
                            $flagPart = false;
                            if(isset($section['part_'.$part_id]) && count($section['part_'.$part_id])>0) {
                                if(isset($section['part_'.$part_id]) && isset($section['part_'.$part_id]['elements'])) {
                                    $elements = $section['part_'.$part_id]['elements'];
                                    $videos = [];
                                    $dataVideo['section_part'] = $section_id;
                                    $dataVideo['section_name'] = $section['section']['name'];
                                    $dataVideo['title'] = '';
                                    if(isset($section['title']))
                                        $dataVideo['title'] = $section['title'];
                                    $dataVideo['part_id'] = $part_id;
                                    foreach($elements as $element) {
                                        if($element['type'] =='video-element') {
                                            $flagPart = true;
                                            $flag = true;
                                            array_push( $videos,$element);
                                            $dataVideo['video'] = $videos;
                                        }
                                    }
                                }
                            }
                            if($flagPart)
                                array_push( $part,$dataVideo);
                        }
                    }

                }
            if($flag){
                $data['order'] = $moment->order;
                $data['moment_id'] = $moment->id;
                $data['moment_name'] = $moment->name;
                $data['url_image_experience'] = $moment->url_image_experience;
                $data['parts'] = $part;
                array_push($datas ['moments'],$data);
            }

        }
        $keys = array_column($datas ['moments'], 'order');
        array_multisort($keys, SORT_ASC, $datas ['moments']);

        return response()->json([
            'status' => 'successfull',
            'message' => 'Datos consultados',
            'data' => $datas
        ]);

    }

    /**
     * @param $account_service_id
     * @param bool $validation_moments
     * @param null $sequence_id
     * @param null $moment_id
     */
    public function validation_access_sequence_content($empresa,$account_service_id, $sequence_id = null, $moment_id = null, $section_type = null)
    {
        if(auth('afiliadoempresa') && auth('afiliadoempresa')->user() ) {
            
            $company = Companies::where('nick_name',$empresa)->first();
            $user = auth('afiliadoempresa')->user();
            $validation_moment = $moment_id != null; 
            
            $AfiliadoEmpresaRolesId = AffiliatedCompanyRole::
                where([
                    ['affiliated_company_id',$user->id],
                    ['company_id',$company->id],
                    ['rol_id', 1] //estudiante
                ])->first();
            
            $tutor_company_id = ConectionAffiliatedStudents::
                        where('student_company_id',$AfiliadoEmpresaRolesId->id)
                        ->pluck('tutor_company_id')->first();

            $tutor = AffiliatedCompanyRole::
                where([
                    ['company_id',$company->id],
                    ['rol_id', 3], //estudiante
                    ['id',$tutor_company_id]
                ])
                ->with('retrive_afiliado_empresa')
                ->first();

            $tutor_id = $tutor->retrive_afiliado_empresa->id;
            
            if($validation_moment) {

                $affiliatedAccountService = AffiliatedAccountService::
                    whereHas('affiliated_content_account_service', function ($query) use ($moment_id) {
                        $query->where('moment_id', $moment_id);
                    })
                    ->where([
                        ['init_date', '<=', Carbon::now()],
                        ['end_date', '>=', Carbon::now()],
                        ['company_affiliated_id', $tutor_id],
                    ])->find($account_service_id);
                   
                    if($affiliatedAccountService && $affiliatedAccountService->rating_plan_type == 3 && $section_type != 3) {
                        return $this->finishValidate('no tiene permiso para acceder a este momento', $sequence_id, $moment_id);
                    }
            }
            else {
                $affiliatedAccountService = AffiliatedAccountService::
                    whereHas('affiliated_content_account_service', function ($query) use ($sequence_id) {
                        $query->where('sequence_id', $sequence_id);
                    })
                    ->where([
                        ['init_date', '<=', Carbon::now()],
                        ['end_date', '>=', Carbon::now()],
                        ['company_affiliated_id', $tutor_id],
                    ])->find($account_service_id);
                    
            }

            if($affiliatedAccountService == null) {
                return $this->finishValidate('no tiene permiso para acceder a este módulo', $sequence_id);
            }
            
            /*if ($affiliatedAccountService->exists() && $AfiliadoEmpresaRolesId->exists()) {
                //if ($affiliatedAccountService->rating_plan_type == 1 || $affiliatedAccountService->rating_plan_type == 2) {//tiene acceso a plan por secuencia o por momentos
                    $afiliadoEmpresa = AfiliadoEmpresa::whereHas('affiliated_company', function ($query) use ($AfiliadoEmpresaRolesId) {
                        $query->whereHas('conection_tutor', function ($query) use ($AfiliadoEmpresaRolesId) {
                            $query->where('student_company_id', $AfiliadoEmpresaRolesId->id);
                        })->where('rol_id', 3);
                    })->find($affiliatedAccountService->company_affiliated_id);
                    if (!$afiliadoEmpresa->exists())
                        return $this->finishValidate('no tiene permiso para ingresar, no esta vinculado para ver este contenido');
                    if ($validation_moments) {
                        if (isset($affiliatedAccountService->affiliated_content_account_service)) {
                            if (count($affiliatedAccountService->affiliated_content_account_service->where(
                                    'sequence_id', $sequence_id
                                )->where('moment_id', $moment_id)) == 0) {
                                return $this->finishValidate('no tiene permiso para acceder a este momento', $sequence_id, $moment_id);
                            }
                            else {
                                if($affiliatedAccountService->rating_plan_type == 3 && $section_type != 3) {
                                    return $this->finishValidate('no tiene permiso para acceder a este momento', $sequence_id, $moment_id);
                                }
                            }
                        } else {
                            return $this->finishValidate('algo salio mal asignando los contenidos del plan, comunicarse con conexiones');
                        }
                    }
                } else {
                    return $this->finishValidate('No tiene permiso para ingresar, no es plan por secuencias ni por momentos');
                }
            } else {
                return $this->finishValidate('No tiene permiso para ingresar al módulo');
            }    */
        }
        else {
            return $this->finishValidate('Sesion inactiva, por favor inicie nuevamente sesion');
        }
    }

    /**
     * @param $account_service_id
     * @param $sequence_id
     * @param $moment_id
     */
    public function validation_access_moment($account_service_id, $sequence_id, $moment_id)
    {
        $affiliatedAccountService = AffiliatedAccountService::with('affiliated_content_account_service')->
            where('init_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())->find($account_service_id);
            return count($affiliatedAccountService->affiliated_content_account_service->where('sequence_id', $sequence_id)->where('moment_id', $moment_id)) > 0;
    }

    function sectionExperiencesInMoment($moment) {
        $section1 =  json_decode($moment->section_1,true) ;
        $section2 =  json_decode($moment->section_2,true) ;
        $section3 =  json_decode($moment->section_3,true) ;
        $section4 =  json_decode($moment->section_4,true) ;
        $section_id = 1;

        if($section1['section']['type'] == 3 ){
            $section_id = 1;
        }else if($section2['section']['type'] == 3 ){
            $section_id = 2;
        } else if ($section3['section']['type'] == 3){
            $section_id = 3;
        }else if ($section4['section']['type'] == 3){
            $section_id = 4;
        } 

        return $section_id;
    }

    function lastPartInSection($moment, $section_id) {
        
        $last_part_id = 0;

        foreach (json_decode($moment['section_'.$section_id], true) as $key => $value) {
                                
            if (strpos('_' . $key, 'part_') != false) {
                
                $num = (int)str_replace('part_', '', $key); 
                if ($num > $last_part_id && isset($value['elements']) && count($value['elements'])>0) {
                    $last_part_id = $num;
                }
            }
        } 
        return $last_part_id;
    }

    
    
    /**
     * @param $account_service_id
     * @param $sequence_id
     * @param $moment_id
     */
    public function finishValidate($message)
    {    
            return view('page404',['message'=>$message]);    
    }
    

}
