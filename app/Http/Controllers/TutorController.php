<?php

namespace App\Http\Controllers;

use App\Models\AffiliatedCompanyRole;
use App\Models\AfiliadoEmpresa;
use App\Models\Companies;
use App\Models\ConectionAffiliatedStudents;
use App\Models\AffiliatedAccountService;
use App\Models\AffiliatedContentAccountService;
use App\Traits\CreateUserRelations;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartProduct;
use App\Traits\RelationRatingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\AdvanceLine;
use App\Models\CompanySequence;
use App\Models\Answer;
use App\Models\Rating;
use App\Models\Question;
use DB;

/**
 * Class TutorController
 * @package App\Http\Controllers
 */
class TutorController extends Controller
{
    //
    use CreateUserRelations;
    use RelationRatingPlan;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['tutor']);
        $route = route('tutor.registerStudentForm', session('name_company'));
        $tutor = AfiliadoEmpresa::find(auth('afiliadoempresa')->user()->id);
        if($request->session()->has('status_validation_free_plan'))
        {
            $statusValidationFreePlan = $request->session()->pull('status_validation_free_plan');
            return view('roles.tutor.profile')->with('route', $route)->with('tutor', $tutor)->with('statusValidationFreePlan',$statusValidationFreePlan);
        }else{

            return view('roles.tutor.profile')->with('route', $route)->with('tutor', $tutor)->with('statusValidationFreePlan',3);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegisterStudentForm(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['tutor']);
        return view('roles.tutor.registerStudent');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showInscriptions(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['tutor']);
        $tutor = AfiliadoEmpresa::find(auth('afiliadoempresa')->user()->id);
        return view('roles.tutor.inscriptions')->with('tutor', $tutor);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProducts(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['tutor']);
        $tutor = AfiliadoEmpresa::find(auth('afiliadoempresa')->user()->id);
        return view('roles.tutor.products')->with('tutor', $tutor);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showHistory(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['tutor']);
        $tutor = AfiliadoEmpresa::find(auth('afiliadoempresa')->user()->id);
        return view('roles.tutor.history')->with('tutor', $tutor);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showWishList(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['tutor']);
        $tutor = AfiliadoEmpresa::find(auth('afiliadoempresa')->user()->id);
        return view('roles.tutor.wish_list')->with('tutor', $tutor);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register_student(Request $request)
    {

        $request->user('afiliadoempresa')->authorizeRoles(['tutor']);
        $quantityStudents = AffiliatedCompanyRole::with('conection_tutor')->has('conection_tutor')->where([
            ['affiliated_company_id',auth('afiliadoempresa')->user()->id],
            ['company_id',1]
        ])->first();
        if($quantityStudents === null){
            $rol = "student";
            $this->create_user_relation(auth('afiliadoempresa')->user(), $request, $rol);
            return response()->json(['status'=>200]);
        }else{
            if(count($quantityStudents->conection_tutor) < 3 ){
                $rol = "student";
                $this->create_user_relation(auth('afiliadoempresa')->user(), $request, $rol);
                return response()->json(['status'=>200]);
            }
            return response()->json(['status'=>403]);
        }

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function get_students_tutor(Request $request)
    {

        $company = Companies::where('nick_name', session('name_company'))->first();
        $user_id = auth('afiliadoempresa')->user()->id;
        $affiliatedCompanyRole = AffiliatedCompanyRole::where([
            ['affiliated_company_id', $user_id],
            ['company_id', $company->id],
            ['rol_id', 3]
        ])->first();

        $students = AfiliadoEmpresa::whereHas('affiliated_company', function ($query) use ($affiliatedCompanyRole, $company) {
            $query->whereHas('conection_students', function ($query) use ($affiliatedCompanyRole) {
                $query->where('tutor_company_id', $affiliatedCompanyRole->id);
            })->where('company_id', $company->id);
        })->get();

        return $students;
    }

    /**
     * @param Request $request
     * @return AffiliatedContentAccountService[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get_products_tutor(Request $request)
    {

        $user_id = auth('afiliadoempresa')->user()->id;
        //esta consulta no se esta realizando
        /*
        $accountServices = AffiliatedAccountService::
        where('affiliated_account_services.company_affiliated_id', '=', $user_id)
            ->where('init_date', '<=', date('Y-m-d') . ' 00:00:00')
            ->where('end_date', '>=', date('Y-m-d') . ' 24:59:59')
            ->get();
        */
        
        /*$ids = AffiliatedAccountService::
        where('company_affiliated_id', '=', $user_id)
            ->where([
                ['init_date', '<=', date('Y-m-d') ],
                ['end_date', '>=', date('Y-m-d') ]
         ])->pluck('id');*/
        
        
        /*$services =  AffiliatedContentAccountService::with('affiliated_account_services.rating_plan', 'sequence')
        ->whereIn('affiliated_account_service_id', $ids)
        ->groupBy('sequence_id','created_at')
        ->get();*/
        
        /*$services = AffiliatedAccountService::
        with('affiliated_content_account_service','rating_plan', 'sequence')
        ->whereIn('affiliated_account_service_id', $ids)
        ->get();*/
        
        $accountServices = AffiliatedAccountService::
        with(['affiliated_content_account_service.sequence'=>function($query){
            $query->select('id','url_image','name');
        }, 'rating_plan'])
        ->where([
                ['company_affiliated_id', $user_id],
                ['init_date', '<=', date('Y-m-d') ],
                ['end_date', '>=', date('Y-m-d') ]])
        ->get();
        return $accountServices;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_history_tutor(Request $request)
    {
        $shoppingCarts = ShoppingCart::
        with('payment_status', 'rating_plan', 'shopping_cart_product')->
        where([
            ['company_affiliated_id', $request->user('afiliadoempresa')->id],
            ['payment_status_id', '!=', 1],
        ])->orderBy('payment_init_date', 'DESC')->get();

        $shoppingCarts = $this->relation_rating_plan($shoppingCarts);
        //return $shoppingCarts;
        return response()->json(['data' => $shoppingCarts], 200);
    }

    /**
     * @param Request $request
     * @param $company
     * @param $password
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate_password(Request $request, $company, $password)
    {

        $afiliadoEmpresa = AfiliadoEmpresa::where([
            ['id', auth('afiliadoempresa')->user()->id],
        ])->first();

        if (!($afiliadoEmpresa === null)) {
            if (Hash::check($password, $afiliadoEmpresa->password))
                return response()->json(['validation' => true, 'message' => 'contraseña actual correcta'], 200);
            else
                return response()->json(['validation' => false, 'message' => 'la contraseña actual no es correcta'], 200);
        } else {
            return response()->json(['validation' => false, 'message' => 'No tiene permisos para realizar esta acción'], 200);
        }

    }

    /**
     * @param Request $request
     * @param $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_password(Request $request, $company)
    {

        $validation = $this->validate_password($request, $company, $request->password1);
        if ($validation->isSuccessful()) {
            $response = json_decode($validation->content());
            if ($response->validation) {
                $update = AfiliadoEmpresa::where([
                    ['id', auth('afiliadoempresa')->user()->id],
                ])->update(array('password' => Hash::make($request->password2)));
                if ($update) {
                    return response()->json(['validation' => true, 'message' => 'contraseña actualizada'], 200);
                } else {
                    return response()->json(['validation' => false, 'message' => 'Algo salio mal, intente de nuevo'], 400);
                }
            } else {
                return response()->json(['validation' => $response->validation, 'message' => $response->message], 400);
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit_column_tutor(Request $request)
    {

        if (AfiliadoEmpresa::where('id', auth('afiliadoempresa')->user()->id)->update(array(
            $request->column => $request->data
        ))) {
            return response()->json([
                'message' => 'Campo editado exitosamente',
                'column' => $request->column,
                'data' => $request->data
            ], 200);
        } else {
            return response()->json(['message' => 'algo salio mal, intente de nuevo'], 200);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit_image_perfil(Request $request)
    {
        if ($request->hasFile('image')) {

            if ($request->file('image')->isValid()) {
                $destinationPath = 'users/avatars/';
                $extension = $request->file('image')->getClientOriginalExtension();
                if($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg'){
                    $fileName = auth('afiliadoempresa')->user()->id . '.' . $extension;
                    $request->file('image')->move($destinationPath, $fileName);

                    $afiliadoempresa = AfiliadoEmpresa::find(auth('afiliadoempresa')->user()->id);
                    $afiliadoempresa->url_image = asset('/users/avatars/') . '/' . $fileName;
                    $afiliadoempresa->save();
                    return response()->json(['valid' => true, 'imagenNueva' => $afiliadoempresa->url_image]);
                }
                return response()->json(['valid' => false,'message'=>'El formato no es valido, formatos permitidos JPG , PNG , JPEG']);
            } else {
                return response()->json(['valid' => false,'message'=>'No fue posible cargar la imagen']);
            }

        } else {
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $extension = 'jpeg';
            $fileName = 'tutor-' . auth('afiliadoempresa')->user()->id . '.' . $extension;
            $afiliadoempresa = AfiliadoEmpresa::find(auth('afiliadoempresa')->user()->id);
            $afiliadoempresa->url_image ='/images/users_images/' . $fileName;
            $directory = env('ADMIN_DESIGN_PATH') . '/../..';
            file_put_contents( $directory. $afiliadoempresa->url_image, $data);
            $afiliadoempresa->save();
            return response()->json(['valid' => true, 'imagenNueva' => $afiliadoempresa->url_image]);
            //return response()->json(['valid' => false,'message'=>'No fue posible cargar la imagen']);
        }
    }
    
    /**
     * @param Request $request
     * @param $empresa
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_achievements(Request $request, $empresa)
    {    $company = Companies::where('nick_name', $empresa)->first();
        $students = $this->get_students_tutor($request);
        foreach($students as $student) {
            $advanceLine = AdvanceLine::with(['affiliated_account_service' => function ($query) {
                $query->where('init_date', '>=', date('Y-m-d'))
                    ->where('end_date', '<=', date('Y-m-d', strtotime('+ 1 day')));
            }])->where('affiliated_company_id', $student->id)->get();
            
            $updated_at = $advanceLine->min('updated_at');
            if($updated_at) {
                $date =  Carbon::parse($updated_at);
                $student['firstMoment'] = $date->format("Y-m-d H:i");
            }
            
            $updated_at = $advanceLine->max('updated_at');
            if($updated_at) {
                $date =  Carbon::parse($updated_at);
                $student['lastMoment'] = $date->format("Y-m-d H:i");
            }
        }
        return view('roles.tutor.achievements.index', ['students'=>$students]);
    }
    
    /**
     * @param Request $request
     * @param $empresa
     * @param int $affiliated_account_service_id
     * @param int $sequence_id
     * @param int $student_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_achievements_student(Request $request, $empresa, $student_id )
    {
        
        $company = Companies::where('nick_name', $empresa)->first();
        $student = AfiliadoEmpresa::find($student_id);
        $accountServices = $this->get_available_sequences($request, $empresa, $company->id);
    
        $advanceLine = AdvanceLine::with(['affiliated_account_service' => function ($query) {
            $query->where('init_date', '>=', date('Y-m-d'))
                ->where('end_date', '<=', date('Y-m-d', strtotime('+ 1 day')));
        }])->where('affiliated_company_id', $student->id)->get();
        
        $updated_at = $advanceLine->min('updated_at');
        if($updated_at) {
            $date =  Carbon::parse($updated_at);
            $student['firstMoment'] = $date->format("Y-m-d H:i");
        }
        
        $updated_at = $advanceLine->max('updated_at');
        if($updated_at) {
            $date =  Carbon::parse($updated_at);
            $student['lastMoment'] = $date->format("Y-m-d H:i");
        }
        
        foreach($accountServices as $accountService) {  
            $accountService['sequence'] = clone $accountService->affiliated_content_account_service[0]->sequence;
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($accountService, $student->id, $accountService['sequence']->id);
            $accountService['sequence']['progress'] = $result['sequence']['progress'];
            $accountService['sequence']['performance'] = $result['sequence']['performance'];
        } 
    
   
        return view('roles.tutor.achievements.student', ['student'=>$student, 'accountServices'=>$accountServices]);
    }
    
    /**
     * @param Request $request
     * @param $empresa
     * @param int $affiliated_account_service_id
     * @param int $sequence_id
     * @param int $student_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_achievements_sequence(Request $request, $empresa, $affiliated_account_service_id, $sequence_id, $student_id )
    {
        
        $tutor = $request->user('afiliadoempresa');
        $company = Companies::where('nick_name', $empresa)->first();
        $accountServices = $this->get_available_sequences($request, $empresa, $company->id, $affiliated_account_service_id);
        if( count($accountServices) < 1) {
            return $this->finishValidate('No tiene permiso para acceder a este módulo');
        }
        
        $student = AfiliadoEmpresa::find($student_id);
        if( $student == null) {
            return $this->finishValidate('Error relacionando al estudiante');
        }
        $accountService = $accountServices[0];
        $sequence = clone $accountService->affiliated_content_account_service[0]->sequence; 
 
        $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($accountService, $student->id, $sequence->id);
        $sequence['progress'] = $result['sequence']['progress'];
        $sequence['performance'] = $result['sequence']['performance'];
        
        $advanceLine = AdvanceLine::with(['affiliated_account_service' => function ($query) {
            $query->where('init_date', '>=', date('Y-m-d'))
                ->where('end_date', '<=', date('Y-m-d', strtotime('+ 1 day')));
        }])->where([
        ['affiliated_account_service_id', $affiliated_account_service_id],
        ['affiliated_company_id', $student->id]])
        ->get();
        
        $updated_at = $advanceLine->min('updated_at');
        if($updated_at) {
            $date =  Carbon::parse($updated_at);
            $student['firstMoment'] = $date->format("Y-m-d H:i");
        }
        
        $updated_at = $advanceLine->max('updated_at');
        if($updated_at) {
            $date =  Carbon::parse($updated_at);
            $student['lastMoment'] = $date->format("Y-m-d H:i");
        }        
        

        $moments = [];
        foreach($sequence->moments as $moment) {
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressMoment($accountService, $student_id, $sequence->id, $moment->id, $moment->order);
            if($result['moment']['isAvailable']) {
                $moment['progress'] = $result['moment']['progress'];
                $moment['performance'] = $result['moment']['performance'];
                $moment['isAvailable'] = $result['moment']['isAvailable'];
            }
            
            array_push($moments,$moment);
        }
       
        return view('roles.tutor.achievements.sequence', ['student' => $student, 'sequence'=>$sequence, 'moments' => $moments, 'affiliated_account_service_id' => $affiliated_account_service_id] );
    }
    
    /**   
     * @param Request $request
     * @param $empresa
     * @param int $affiliated_account_service_id
     * @param int $sequence_id
     * @param int $student_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_achievements_moment(Request $request, $empresa, $affiliated_account_service_id, $sequence_id, $student_id)
    {
        
        $tutor = $request->user('afiliadoempresa');
        $company = Companies::where('nick_name', $empresa)->first();
        $student = AfiliadoEmpresa::find($student_id);
        $accountServices = $this->get_available_sequences($request, $empresa, $company->id, $affiliated_account_service_id);
        if( count($accountServices) < 1) {
            return $this->finishValidate('No tiene permiso para acceder a este módulo');
        }
        
        $accountService = $accountServices[0];
        $sequence = clone $accountService->affiliated_content_account_service[0]->sequence; 
 
        $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($accountService, $student_id, $sequence->id);
        $sequence['progress'] = $result['sequence']['progress'];
        $sequence['performance'] = $result['sequence']['performance'];
 
        $advanceLine = AdvanceLine::with(['affiliated_account_service' => function ($query) {
                $query->where('init_date', '>=', date('Y-m-d'))
                    ->where('end_date', '<=', date('Y-m-d', strtotime('+ 1 day')));
            }])->where([
            ['affiliated_account_service_id', $affiliated_account_service_id],
            ['affiliated_company_id', $student->id]])
            ->get();
        
        $updated_at = $advanceLine->min('updated_at');
        if($updated_at) {
            $date =  Carbon::parse($updated_at);
            $student['firstMoment'] = $date->format("Y-m-d H:i");
        }
        
        $updated_at = $advanceLine->max('updated_at');
        if($updated_at) {
            $date =  Carbon::parse($updated_at);
            $student['lastMoment'] = $date->format("Y-m-d H:i");
        }        
        
        $affiliatedAccountService = 
            AffiliatedAccountService::with('affiliated_content_account_service')->
                where('init_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
            ->find($affiliated_account_service_id);
    
        $moments = [];
        foreach($sequence->moments as $moment) {
            
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressMoment($accountService, $student_id, $sequence->id, $moment->id, $moment->order);
             
            $moment['isAvailable'] = $result['moment']['isAvailable'];
           
            if($moment['isAvailable'] ) {
                $moment['lastAccessInMoment'] = $result['moment']['lastAccessInMoment'];
                $moment['progress'] = $result['moment']['progress'];
                $moment['performance'] = $result['moment']['performance'];

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
                    $result = app('App\Http\Controllers\AchievementController')->retriveProgressSection($accountService, $student_id, $sequence->id, $moment->id, $moment->order, $section_id);
                    $section['progress'] = $result['section']['progress'];
                    if(isset($result['section']['performance'])) {
                        $section['performance'] =   $result['section']['performance'];
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
                $moment['sections'] = $sections;
                //
            }
           
            array_push($moments,$moment);
           
        }  
        
        return view('roles.tutor.achievements.moment', ['student' => $student, 'sequence'=>$sequence, 'moments' => $moments, 'affiliated_account_service_id' => $affiliated_account_service_id]  );
    }
    
    /**   
     * @param Request $request
     * @param $empresa
     * @param int $affiliated_account_service_id
     * @param int $sequence_id
     * @param int $student_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_achievements_question(Request $request, $empresa, $affiliated_account_service_id, $sequence_id, $student_id )
    {
        $tutor = $request->user('afiliadoempresa');
        $student = AfiliadoEmpresa::find($student_id);
        $company = Companies::where('nick_name', $empresa)->first();
        $accountServices = $this->get_available_sequences($request, $empresa, $company->id, $affiliated_account_service_id);
        
        if( count($accountServices) < 1) {
            return $this->finishValidate('No tiene permiso para acceder a este módulo');
        }
        
        $accountService = $accountServices[0];
        $sequence = clone $accountService->affiliated_content_account_service[0]->sequence; 
 

        $result = app('App\Http\Controllers\AchievementController')->retriveProgressSequence($accountService, $student->id, $sequence->id);
        $sequence['progress'] = $result['sequence']['progress'];
        $sequence['performance'] = $result['sequence']['performance'];
        
        $advanceLine = AdvanceLine::with(['affiliated_account_service' => function ($query) {
            $query->where('init_date', '>=', date('Y-m-d'))
                ->where('end_date', '<=', date('Y-m-d', strtotime('+ 1 day')));
        }])->where([
        ['affiliated_account_service_id', $accountService->id],
        ['affiliated_company_id', $student->id]])
        ->get();
        
        $updated_at = $advanceLine->min('updated_at');
        if($updated_at) {
            $date =  Carbon::parse($updated_at);
            $student['firstMoment'] = $date->format("Y-m-d H:i");
        }
        
        $updated_at = $advanceLine->max('updated_at');
        if($updated_at) {
            $date =  Carbon::parse($updated_at);
            $student['lastMoment'] = $date->format("Y-m-d H:i");
        }
        
        $moments = [];
        
        
        $evidences = Rating::with('answers.question')
        ->where([
            ['sequence_id',$sequence->id],
            ['student_id',$student->id],
            ['affiliated_account_service_id',$accountService->id],
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
            
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressMoment($accountService, $student->id, $sequence->id, $moment->id, $moment->order);
            $moment['isAvailable'] = $result['moment']['isAvailable'];
            if($result['moment']['isAvailable']) {
                $moment['progress'] = $result['moment']['progress'];
                $moment['performance'] = $result['moment']['performance'];
                $moment['lastAccessInMoment'] = $result['moment']['lastAccessInMoment'];
            }
            
            $moment['ratings'] = $rating;
           
            array_push($moments,$moment);
        }
        return view('roles.tutor.achievements.questions', ['student' => $student, 'sequence'=>$sequence, 'moments' => $moments, 'affiliated_account_service_id' => $affiliated_account_service_id]  );
    }

    
    /**
     * @param Request $request
     * @param $empresa
     * @param $company_id
     * @param $accountService_id
     * @return AffiliatedContentAccountService[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get_available_sequences(Request $request, $empresa, $company_id, $accountService_id= null)
    { 
        $tutor = $request->user('afiliadoempresa');
         
        $accountServices = AffiliatedAccountService::
        with('rating_plan','affiliated_content_account_service.sequence')
        ->where('company_affiliated_id',  $tutor->id)
        ->select(DB::raw('*,(CASE WHEN init_date <= CURRENT_DATE and end_date >= CURRENT_DATE  THEN 1 ELSE 0 END) AS is_active'))
        ->orderBy('end_date', 'desc'); 
        /*->where([
            ['init_date', '<=', Carbon::now()],
            ['end_date', '>=', Carbon::now()]
        ]);*/
        if(  $accountService_id != null) {
            $accountServices = $accountServices->where('id',$accountService_id);
        } 
        return $accountServices->get();
    }

}
