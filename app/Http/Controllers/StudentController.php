<?php

namespace App\Http\Controllers;

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
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Models\CompanySequence;
use App\Models\SequenceMoment;
use App\Models\Achievement;

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

    public function show_available_experiences(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        return view('roles.student.available_experiences');
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
        $accountServices = $this->get_available_sequences($request, $empresa, $company_id, true);
        $countSequences = count($accountServices);
        foreach($accountServices as $accountService) { 
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($accountService->affiliated_account_service_id, $student->id, $accountService->sequence->id);
            $accountService->sequence['progress'] = $result['sequence']['progress'];
            $accountService->sequence['performance'] = $result['sequence']['performance'];
        } 
        $firstAccess = $student->first_last_access()['first'];
        $lastAccess = $student->first_last_access()['last'];

        return view('roles.student.achievements.index', ['accountServices'=> $accountServices, 'student' => $student, 'countSequences' => $countSequences, 'firstAccess' => $firstAccess, 'lastAccess' => $lastAccess]);
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
        $sequences = $this->get_available_sequences($request, $empresa, $company_id, true);
        $countSequences = count($sequences);
        $firstAccess = $student->first_last_access()['first'];
        $lastAccess = $student->first_last_access()['last'];
       
        $sequence = CompanySequence::with('moments')->find($sequence_id);
 
        $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($affiliated_account_service_id, $student->id, $sequence->id);
        $sequence['progress'] = $result['sequence']['progress'];
        $sequence['performance'] = $result['sequence']['performance'];

        $moments = [];
        foreach($sequence->moments as $moment) {
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressMoment($affiliated_account_service_id, $student->id, $sequence->id, $moment->id, $moment->order);
            $moment['progress'] = $result['moment']['progress'];
            $moment['performance'] = $result['moment']['performance'];
            array_push($moments,$moment);
        }
       
        return view('roles.student.achievements.sequence', ['student' => $student, 'countSequences' => $countSequences, 'firstAccess' => $firstAccess, 'lastAccess' => $lastAccess, 'sequence'=>$sequence, 'moments' => $moments, 'affiliated_account_service_id' => $affiliated_account_service_id] );
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
        $sequences = $this->get_available_sequences($request, $empresa, $company_id, true);
        $countSequences = count($sequences);
        $firstAccess = $student->first_last_access()['first'];
        $lastAccess = $student->first_last_access()['last'];
        
        $sequence = CompanySequence::with('moments')->find($sequence_id);
        $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($affiliated_account_service_id, $student->id, $sequence->id);
        $sequence['progress'] = $result['sequence']['progress'];
        $sequence['performance'] = $result['sequence']['performance'];
        
        $moments = [];
        foreach($sequence->moments as $moment) {
            
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressMoment($affiliated_account_service_id, $student->id, $sequence->id, $moment->id, $moment->order);
            $moment['progress'] = $result['moment']['progress'];
            $moment['performance'] = $result['moment']['performance'];
            $moment['lastAccessInMoment'] = $result['moment']['lastAccessInMoment'];
            

            $section_1 = json_decode($moment->section_1,true);
            $section_2 = json_decode($moment->section_2,true);
            $section_3 = json_decode($moment->section_3,true);
            $section_4 = json_decode($moment->section_4,true);
            $sections = [
                'section_1' => ['name' => $section_1['section']['name'],'title' => isset($section_1['title']) ? $section_1['title'] : '', 'section' => $section_1],
                'section_2' => ['name' => $section_2['section']['name'],'title' => isset($section_2['title']) ? $section_2['title'] : '', 'section' => $section_2],
                'section_3' => ['name' => $section_3['section']['name'],'title' => isset($section_3['title']) ? $section_3['title'] : '', 'section' => $section_3],
                'section_4' => ['name' => $section_4['section']['name'],'title' => isset($section_4['title']) ? $section_4['title'] : '', 'section' => $section_4],
            ]; 
            $section_id=1; 
            foreach($sections as &$section) {
                $result = app('App\Http\Controllers\AchievementController')->retriveProgressSection($affiliated_account_service_id, $student->id, $sequence->id, $moment->id, $moment->order, $section_id);
                $section['progress'] = $result['section']['progress'];
                $section['performance'] = $result['section']['performance'];
                $section_id ++;
            }

            $moment['sections'] = $sections;
            array_push($moments,$moment);
        }
        
        return view('roles.student.achievements.moment', ['student' => $student, 'countSequences' => $countSequences, 'firstAccess' => $firstAccess, 'lastAccess' => $lastAccess, 'sequence'=>$sequence, 'moments' => $moments, 'affiliated_account_service_id' => $affiliated_account_service_id]  );
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
        $sequences = $this->get_available_sequences($request, $empresa, $company_id, true);
        $countSequences = count($sequences);
        $firstAccess = $student->first_last_access()['first'];
        $lastAccess = $student->first_last_access()['last'];
        
        $sequence = CompanySequence::with('moments')->find($sequence_id);

        $sequence = CompanySequence::with('moments')->find($sequence_id);
        $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($affiliated_account_service_id, $student->id, $sequence->id);
        $sequence['progress'] = $result['sequence']['progress'];
        $sequence['performance'] = $result['sequence']['performance'];
        
        $moments = [];
        
        
        $evidences = Rating::with('answers.question')
        ->where([
            ['sequence_id',$sequence->id],
            ['student_id',$student->id],
            ['affiliated_account_service_id',$affiliated_account_service_id],
        ])->get();
        
        foreach($sequence->moments as $moment) {
            $rating = [];
            
            foreach([1,2,3,4] as $section_id) {
                
                $section = json_decode($moment['section_'.$section_id], true);
                foreach([1,2,3,4,5] as $part_id) {
                    if(isset($section['part_'.$part_id]) && count($section['part_'.$part_id])>0) {
                        if(isset($section['part_'.$part_id]) && isset($section['part_'.$part_id]['elements'])) {
                            $elements = $section['part_'.$part_id]['elements'];
                            foreach($elements as $element) {
                                if($element['type'] =='evidence-element' && $element['questionEditType'] != 1 ) {
                                    $rating[$element['id']] = ['element'=>$element];
                                    $rating[$element['id']]['evidences'] = $evidences->where('experience_id',$element['id'])->first();
                                    if($rating[$element['id']]['evidences']) {
                                        //dd($rating[$element['id']]['evidences']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressMoment($affiliated_account_service_id, $student->id, $sequence->id, $moment->id, $moment->order);
            $moment['progress'] = $result['moment']['progress'];
            $moment['performance'] = $result['moment']['performance'];
            $moment['lastAccessInMoment'] = $result['moment']['lastAccessInMoment'];
            
            $moment['ratings'] = $rating;
            array_push($moments,$moment);
        }
        return view('roles.student.achievements.questions', ['student' => $student, 'countSequences' => $countSequences, 'firstAccess' => $firstAccess, 'lastAccess' => $lastAccess, 'sequence'=>$sequence, 'moments' => $moments, 'affiliated_account_service_id' => $affiliated_account_service_id]  );
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
        
        $validate = $this->validation_access_sequence_content($account_service_id);
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
    
            $data = array_merge(['sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext,'sectionParts'=>$sections, 'part_id'=>$part_id,'section_part_id'=>$section_part_id], $section);
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
        
        $validate = $this->validation_access_sequence_content($account_service_id);
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
            $data = array_merge(['sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext,'sectionParts'=>$sections, 'part_id'=>$part_id, 'section_part_id'=>$section_part_id], $section);
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

        //$sequence = CompanySequence::where('id',$sequence_id)->get()->first();
        $section_part_id = 3;
        
        $validate = $this->validation_access_sequence_content($account_service_id);
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
            $data = array_merge(['sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext,'sectionParts'=>$sections, 'part_id'=>$part_id, 'section_part_id'=>$section_part_id], $section);
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

        //$sequence = CompanySequence::where('id',$sequence_id)->get()->first();
        
        $validate = $this->validation_access_sequence_content($account_service_id);
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
                        'section' => 1,
                        'order_moment_id' => $next_moment->order,
                        'sequence_id' => $sequence_id]);
                        break;
                    }
                }
            }
            $data = array_merge(['sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext,'sectionParts'=>$sections, 'part_id'=>$part_id, 'section_part_id'=>$section_part_id], $section);
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
    public function show_moment_section(Request $request, $empresa, $account_service_id, $sequence_id, $moment_id, $order_moment_id, $section_id, $part_id = 1)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['student']);
        $moment = SequenceMoment::with('sequence')
            ->where('sequence_moments.sequence_company_id', $sequence_id)
            ->where('sequence_moments.id', $moment_id)
            ->first();
        
        if($moment == null) {
            return $this->finishValidate('Página no encontrada');
        }
        $section = json_decode($moment['section_'.$section_id],true);
        
        if (!(isset($section) && $section != null && $section['section'] != null))  {
                    return $this->finishValidate('Página no encontrada');
        }
        $section_type = $section['section']['type'];

        $validate = $this->validation_access_sequence_content($account_service_id, true, $sequence_id, $moment_id, $section_type);
        if($validate) {
                return $validate;
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
        
        $sequence = CompanySequence::where('id', $sequence_id)->get()->first();
        
        if ($moment['section_' . $section_id]) {
            $section = json_decode($moment['section_' . $section_id], true);
            $section_1 = json_decode($moment->section_1, true);
            $section_2 = json_decode($moment->section_2, true);
            $section_3 = json_decode($moment->section_3, true);
            $section_4 = json_decode($moment->section_4, true);
            $part = json_decode($moment['section_' . $section_id], true)['part_' . $part_id];
            
            $buttonBack = 'none';
            if ($part_id > 1) {
                $buttonBack = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                    'sequence_id' => $sequence_id,
                    'moment_id' => $moment_id,
                    'section_id' => $section_id,
                    'account_service_id' => $account_service_id,
                    'order_moment_id' => $order_moment_id,
                    'part_id' => ($part_id - 1)]);
            } else {
                if($section_id > 1) {
                    $last_part_id = 1;
                    foreach (json_decode($moment['section_'.($section_id - 1)], true) as $key => $value) {
                        if (strpos('_' . $key, 'part_') != false) {
                            $num = (int)str_replace('part_', '', $key);
                            if ($num > $last_part_id && $value) {
                                $last_part_id = $num;
                            }
                        }
                    }
                    $buttonBack = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                        'sequence_id' => $sequence_id,
                        'moment_id' => $moment_id,
                        'section_id' => ( $section_id - 1 ),
                        'account_service_id' => $account_service_id,
                        'order_moment_id' => $order_moment_id,
                        'part_id' => $last_part_id]);
                }
                else {
                    if($order_moment_id == 1) {
                        $buttonBack = route('student.sequences_section_4', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id, 'sequence_id' => $sequence_id]);
                    }
                    else {
                        for($i = $order_moment_id - 1; $i >= 1; $i--) {
                            $last_moment = $sequence->moments[$i-1];
                            $has_moment = $this->validation_access_moment($account_service_id, $sequence_id, $last_moment->id);
                            if($has_moment){
                                $buttonBack = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                                    'sequence_id' => $sequence_id,
                                    'moment_id' => $last_moment->id,
                                    'order_moment_id' => $last_moment->order,
                                    'section_id' => 4,
                                    'part_id' => 1]);
                                break;
                            }
                        }
                    }
                }
            }
           
            if (isset($section['part_' . ($part_id + 1)]) && isset($section['part_' . ($part_id + 1)]['elements']) ) {
                $buttonNext = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                    'sequence_id' => $sequence_id,
                    'moment_id' => $moment_id,
                    'section_id' => $section_id,
                    'account_service_id' => $account_service_id,
                    'order_moment_id' => $order_moment_id,
                    'part_id' => $part_id + 1]);
            } else {
                if($section_id < 4) {
                    $buttonNext = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                        'sequence_id' => $sequence_id,
                        'moment_id' => $moment_id,
                        'section_id' => $section_id + 1,
                        'account_service_id' => $account_service_id,
                        'order_moment_id' => $order_moment_id,
                        'part_id' => 1]);
                }
                else {
                    for($i = $order_moment_id + 1; $i <= count($sequence->moments); $i++) {
                        $next_moment = $sequence->moments[$i-1];
                        $has_moment = $this->validation_access_moment($account_service_id, $sequence_id, $next_moment->id);
                        if($has_moment){
                            $buttonNext = route('student.show_moment_section', ['empresa' => 'conexiones', 'account_service_id' => $account_service_id,
                            'sequence_id' => $sequence_id,
                            'moment_id' => $next_moment->id,
                            'order_moment_id' => $next_moment->order,
                            'section_id' => 1,
                            'part_id' => 1]);
                            break;
                        }
                    }
                }
            }
        
            $data = array_merge(['sequence' => $moment->sequence, 'sequence_id' => $sequence_id,'section_id'=>$section_id,'part_id' => $part_id,
                'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext, 'moment' => $moment, 'sections' => [$section_1, $section_2, $section_3, $section_4]], $section, $part);
            return view('roles.student.content_sequence_section', $data)->with('account_service_id', $account_service_id)->with('order_moment_id', $order_moment_id);
        }
    }

    /**
     * @param Request $request
     * @param $empresa
     * @param $company_id
     * @param $groupBy
     * @return AffiliatedContentAccountService[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get_available_sequences(Request $request, $empresa, $company_id, $groupBy = false)
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
        
        $ids = AffiliatedAccountService::
        with('rating_plan')->whereHas('company_affiliated', function ($query) use ($tutor_id) {
            $query->where('id', $tutor_id->tutor_company_id);
        })->where([
            ['init_date', '<=', Carbon::now()],
            ['end_date', '>=', Carbon::now()]
        ])->pluck('id');
        if($groupBy) { 
          return AffiliatedContentAccountService::with('sequence')->whereIn('affiliated_account_service_id', $ids)->groupBy('sequence_id')->get();
        }
        else {
            return AffiliatedContentAccountService::with('sequence')->whereIn('affiliated_account_service_id', $ids)->get();
        }
        

    }

    public function get_avalible_experiences(Request $request,$company_id,$sequence_id){


        $tutor_id = ConectionAffiliatedStudents::select('id', 'tutor_company_id')
            ->whereHas('student_family', function ($query) use ($request, $company_id) {
                $query->where([
                    ['affiliated_company_id', $request->user('afiliadoempresa')->id],
                    ['company_id', $company_id],
                    ['rol_id', 1]
                ]);
            })->first();
        $ids = AffiliatedAccountService::
        with('rating_plan')->whereHas('company_affilated', function ($query) use ($tutor_id) {
            $query->where('id', $tutor_id->tutor_company_id);
        })->where([
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
        $datas ['moments'] = [];
        foreach($sequences as $sequence) {
            $moment = SequenceMoment::find($sequence->moment_id);
            $data['data'] = [];
            $dataVideo = [];
            $flag = false;
            foreach([1,2,3,4] as $section_id) {
                    $section = json_decode($moment['section_'.$section_id], true);
                    foreach([1,2,3,4,5] as $part_id) {
                        if(isset($section['part_'.$part_id]) && count($section['part_'.$part_id])>0) {
                            if(isset($section['part_'.$part_id]) && isset($section['part_'.$part_id]['elements'])) {
                                $elements = $section['part_'.$part_id]['elements'];
                                $videos = [];
                                foreach($elements as $element) {
                                    if($element['type'] =='video-element') {
                                        $flag = true;
                                        $dataVideo['section_part'] = $section_id;
                                        $dataVideo['section_name'] = $section['section']['name'];
                                        $dataVideo['part_id'] = $part_id;
                                        array_push( $videos,$element);
                                        $dataVideo['video'] = $videos;
                                    }
                                }
                            }
                        }
                    }
                }
            if($flag){
                $data['moment_id'] = $moment->id;
                $data['moment_name'] = $moment->name;
                $data['data'] = $dataVideo;
                array_push($datas ['moments'],$data);
            }

        }


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
    public function validation_access_sequence_content($account_service_id, $validation_moments = false, $sequence_id = null, $moment_id = null, $section_type = null)
    {
        $affiliatedAccountService = AffiliatedAccountService::with('affiliated_content_account_service')->
        where('init_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())->find($account_service_id);

        $AfiliadoEmpresaRolesId = AfiliadoEmpresaRoles::select('id')->where([
            ['affiliated_company_id', auth('afiliadoempresa')->user()->id],
            ['company_id', 1],//conexiones
            ['rol_id', 1]//estudiante
        ])->first();
        
        if ($affiliatedAccountService->exists() && $AfiliadoEmpresaRolesId->exists()) {
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
            /*} else {
                return $this->finishValidate('No tiene permiso para ingresar, no es plan por secuencias ni por momentos');
            }*/
        } else {
            return $this->finishValidate('no tiene permiso para ingresar');
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
            if ($affiliatedAccountService->rating_plan_type == 1) { //Si es plan por secuencia tiene acceso a todos los momentos
                return true;
            }
            else if ($affiliatedAccountService->rating_plan_type == 2 || $affiliatedAccountService->rating_plan_type == 3) { //Si es plan por momento o experiencia se valida el momento 
                return count($affiliatedAccountService->affiliated_content_account_service->where('sequence_id', $sequence_id)->where('moment_id', $moment_id)) > 0;
            }
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
