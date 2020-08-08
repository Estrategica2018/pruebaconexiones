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
        $accountServices = $this->get_available_sequences($request, $empresa, $company_id);
        $countSequences = count($accountServices);
  
        foreach($accountServices as $accountService) {
            //calculando el progreso de la linea de avance
            $advanceLine = AdvanceLine::where([
                ['affiliated_company_id',$student->id],
                ['affiliated_account_service_id',$accountService->affiliated_account_service_id],
                ['sequence_id',$accountService->sequence->id]]
            )->get(); 
            $accountService->sequence['progress'] = 100 * count($advanceLine) / (8*4); 

            //calculando los porcentajes de desempeño
            $ratings = Rating::where([
                ['affiliated_account_service_id',$accountService->affiliated_account_service_id],
                ['student_id', $student->id],
                ['company_id', $accountService->sequence->company_id],
                ['sequence_id',$accountService->sequence->id ] 
            ]);
            if(count($ratings)>0) {
                $performance = $ratings->avg('weighted');
                $accountService->sequence['performance'] = number_format($performance,0);
            }
            else {
                $accountService->sequence['performance'] = 0;
            }
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
        $sequences = $this->get_available_sequences($request, $empresa, $company_id);
        $countSequences = count($sequences);
        $firstAccess = $student->first_last_access()['first'];
        $lastAccess = $student->first_last_access()['last'];
       
        $sequence = CompanySequence::with('moments')->find($sequence_id);
       
        $moments = [];
        foreach($sequence->moments as $moment) {
           
            //calculando el progreso de la linea de avance        
            $advanceLine = AdvanceLine::where([
                ['affiliated_company_id',$student->id],
                ['affiliated_account_service_id',$request->affiliated_account_service_id],
                ['sequence_id',$sequence_id],
                ['moment_order',$moment->order]
            ])->orderBy('moment_order', 'ASC')->orderBy('moment_section_id', 'ASC')->get();
            
            $moment['advance'] = (count($advanceLine) / 4) * 100;; 

            //calculando los porcentajes de desempeño
            $ratings = Rating::where([
                ['affiliated_account_service_id',$affiliated_account_service_id],
                ['student_id',$student->id],
                ['company_id', $sequence->company_id],
                ['sequence_id',$sequence->id],
                ['moment_id',$moment->id]
            ]);

            if(count($ratings)>0) {
                $performance = $ratings->avg('weighted');
                $moment['performance'] = number_format($performance,0);
            } 

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
        $sequences = $this->get_available_sequences($request, $empresa, $company_id);
        $countSequences = count($sequences);
        $firstAccess = $student->first_last_access()['first'];
        $lastAccess = $student->first_last_access()['last'];
        
        $sequence = CompanySequence::with('moments')->find($sequence_id);
        $moments = [];

        // $answers = Answer::with('question')
        //     ->where([['student_affiliated_company_id',$student->id], 
        //           ['affiliated_account_service_id',$affiliated_account_service_id]
        //     ])
        //     ->get();

        foreach($sequence->moments as $moment) {
            $section_1 = json_decode($moment->section_1,true);
            $section_2 = json_decode($moment->section_2,true);
            $section_3 = json_decode($moment->section_3,true);
            $section_4 = json_decode($moment->section_4,true);
 
            
            $advanceLine = AdvanceLine::where([
                ['affiliated_company_id',$student->id],
                ['affiliated_account_service_id',$request->affiliated_account_service_id],
                ['sequence_id',$sequence_id],
                ['moment_order',$moment->order]
            ])->orderBy('moment_order', 'ASC')->orderBy('moment_section_id', 'ASC')->get();

            $moment['advance'] = (count($advanceLine) / 4) * 100;;
            $moment['performance'] = (count($advanceLine) / 4) * 100;
            $moment['lastAccessInMoment'] = $advanceLine->max('updated_at');
 
            $sectionIndex=1;

            $sections = [
                'section_1' => ['name' => $section_1['section']['name'],'title' => isset($section_1['title']) ? $section_1['title'] : '', 'section' => $section_1],
                'section_2' => ['name' => $section_2['section']['name'],'title' => isset($section_2['title']) ? $section_2['title'] : '', 'section' => $section_2],
                'section_3' => ['name' => $section_3['section']['name'],'title' => isset($section_3['title']) ? $section_3['title'] : '', 'section' => $section_3],
                'section_4' => ['name' => $section_4['section']['name'],'title' => isset($section_4['title']) ? $section_4['title'] : '', 'section' => $section_4],
            ]; 

            foreach($sections as &$section) {
              
                //calcula el Progreso según la línea de avance

                $progress =  $advanceLine = AdvanceLine::where([
                    ['affiliated_company_id',$student->id],
                    ['affiliated_account_service_id',$request->affiliated_account_service_id],
                    ['sequence_id',$sequence_id],
                    ['moment_order',$moment->order],
                    ['moment_order',$moment->order],
                    ['moment_section_id',$sectionIndex]
                ])->get();

                if(count($progress) > 0) { 
                    $section['progress'] = 100; 
                }

                //calcula el desempeño según las evaluaciones
 
                $ratings = Rating::where([
                    ['affiliated_account_service_id',$affiliated_account_service_id],
                    ['student_id',$student->id],
                    ['company_id', $sequence->company_id],
                    ['sequence_id',$sequence->id],
                    ['moment_id',$moment->id],
                    ['section',($sectionIndex-1)]
                ])->get();

                $questions = Question::where([
                    ['sequence_id',$sequence->id],
                    ['moment_id',$moment->id],
                    ['section',($sectionIndex-1)]
                ])->get();
                
                if(count($ratings) > 0 || count($questions) > 0 ) { 
                    $section['performance'] = $ratings->avg('weighted') ? $ratings->avg('weighted') : 0;  
                } 
                $sectionIndex ++;
            }

            $moment['sections'] = $sections;    
            array_push($moments,$moment);
        }
   
        return view('roles.student.achievements.moment', ['student' => $student, 'countSequences' => $countSequences, 'firstAccess' => $firstAccess, 'lastAccess' => $lastAccess, 'sequence'=>$sequence, 'moments' => $moments, 'affiliated_account_service_id' => $affiliated_account_service_id]  );
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
        $this->validation_access_sequence_content($account_service_id);
        //$sequence = CompanySequence::where('id',$sequence_id)->get()->first();
        $sequence = $this->sequencesCache->where('id', $sequence_id)->first();
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
            //dd($sectionParts['part_'.($part_id + 1)]['elements']);
            $data = array_merge(['sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext,'sectionParts'=>$sections, 'part_id'=>$part_id], $section);
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
        $this->validation_access_sequence_content($account_service_id);
        //$sequence = CompanySequence::where('id',$sequence_id)->get()->first();
        $sequence = $this->sequencesCache->where('id', $sequence_id)->first();
        if ($sequence->section_2) {
            $section = json_decode($sequence->section_2, true);
            $section = $section['part_' . $part_id];
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
            $data = array_merge(['sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext], $section);
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
        $sequence = $this->sequencesCache->where('id', $sequence_id)->first();
        if ($sequence->section_3) {
            $section = json_decode($sequence->section_3, true);
            $section = $section['part_' . $part_id];
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
            $data = array_merge(['sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext], $section);
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
        $sequence = $this->sequencesCache->where('id', $sequence_id)->first();
        if ($sequence->section_4) {
            $section = json_decode($sequence->section_4, true);
            $section = $section['part_' . $part_id];
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
            $data = array_merge(['sequence' => $sequence, 'buttonBack' => $buttonBack, 'buttonNext' => $buttonNext], $section);
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
        $this->validation_access_sequence_content($account_service_id, true, $sequence_id, $moment_id);
        $moment = SequenceMoment::with('sequence')
            ->where('sequence_moments.sequence_company_id', $sequence_id)
            ->where('sequence_moments.id', $moment_id)
            ->first();

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
     * @return AffiliatedContentAccountService[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get_available_sequences(Request $request, $empresa, $company_id)
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
        with('rating_plan')->whereHas('company_affilated', function ($query) use ($tutor_id) {
            $query->where('id', $tutor_id->tutor_company_id);
        })->where([
            ['init_date', '<=', Carbon::now()],
            ['end_date', '>=', Carbon::now()]
        ])->pluck('id');

        return AffiliatedContentAccountService::with('sequence')->whereIn('affiliated_account_service_id', $ids)->groupBy('sequence_id')->get();

    }

    /**
     * @param $account_service_id
     * @param bool $validation_moments
     * @param null $sequence_id
     * @param null $moment_id
     */
    public function validation_access_sequence_content($account_service_id, $validation_moments = false, $sequence_id = null, $moment_id = null)
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
            if ($affiliatedAccountService->rating_plan_type == 1 || $affiliatedAccountService->rating_plan_type == 2) {//tiene acceso a plan por secuencia o por momentos
                $afiliadoEmpresa = AfiliadoEmpresa::whereHas('affiliated_company', function ($query) use ($AfiliadoEmpresaRolesId) {
                    $query->whereHas('conection_tutor', function ($query) use ($AfiliadoEmpresaRolesId) {
                        $query->where('student_company_id', $AfiliadoEmpresaRolesId->id);
                    })->where('rol_id', 3);
                })->find($affiliatedAccountService->company_affiliated_id);
                if (!$afiliadoEmpresa->exists())
                    dd('no tiene permiso para ingresar, no esta vinculado para ver este contenido');
                if ($validation_moments) {
                    if (isset($affiliatedAccountService->affiliated_content_account_service)) {
                        if (count($affiliatedAccountService->affiliated_content_account_service->where(
                                'sequence_id', $sequence_id
                            )->where('moment_id', $moment_id)) == 0) {
                            dd('no tiene permiso para acceder a este momento', $sequence_id, $moment_id);
                        }
                    } else {
                        dd('algo salio mal asignando los contenidos del plan, comunicarse con conexiones');
                    }
                }
            } else {
                dd('No tiene permiso para ingresar, no es plan por secuencias ni por momentos');
            }
        } else {
            dd('no tiene permiso para ingresar');
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

}
