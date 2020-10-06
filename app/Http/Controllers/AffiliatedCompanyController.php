<?php

namespace App\Http\Controllers;

use App\Mail\SendChangesProfileStudent;
use App\Mail\SendConfirmMailNotification;
use App\Models\AfiliadoEmpresa;
use App\Models\ConectionAffiliatedStudents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Class AffiliatedCompanyController
 * @package App\Http\Controllers
 */
class AffiliatedCompanyController extends Controller
{
    //
    /**
     * @param Request $request
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_user(Request $request, $user_id)
    {

        $afiliadoEmpresa = AfiliadoEmpresa::find($user_id);
        $age_stage = ConectionAffiliatedStudents::select('age_stage')->where(function ($query) use ($afiliadoEmpresa) {
            $query->where('student_company_id', $afiliadoEmpresa->affiliated_company->where(
                'rol_id', 1
            )->where('company_id', 1)->first()->id);
        })->first();
        return response()->json(['data' => $afiliadoEmpresa,
            'age_stage' => $age_stage], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit_user_student(Request $request)
    {
        if (auth('afiliadoempresa')->user()) {
            if (isset($request->id)) {
                $userTutor = AfiliadoEmpresa::with('affiliated_company')
                    ->whereHas('affiliated_company', function ($query) {
                        $query->where([
                            ['rol_id', 3],//tutor-familiar
                            ['company_id', 1]//conexiones
                        ]);
                    })->find(auth('afiliadoempresa')->user()->id);
                if ($userTutor->exists()) {
                    $userStudent = AfiliadoEmpresa::whereHas('affiliated_company', function ($query) use ($userTutor) {
                        $query->whereHas('conection_students', function ($query) use ($userTutor) {
                            $query->where('tutor_company_id', $userTutor->affiliated_company[0]->id);
                        })->where('rol_id', 1);
                    })->find($request->id);
                    if ($userStudent->exists()) {
                        $userStudent->name = $request->name;
                        $userStudent->last_name = $request->last_name;
                        if (isset($request->user_name)) {
                            $exist = AfiliadoEmpresa::where('user_name', $request->user_name)->where('id', '!=', $userStudent->id)->first();
                            if ($exist === null) {
                                $userStudent->user_name = $request->user_name;
                            } else {
                                return response()->json(['data' => '', 'message', 'El nombre de usuario ya esta en uso'], 200);
                            }
                        }
                        if (isset($request->birthday)) {
                            $userStudent->birthday = $request->birthday;
                        }
                        if (isset($request->password)) {
                            $userStudent->password = Hash::make($request->password);
                        }
                        if (isset($request->kidSelected)) {
                            ConectionAffiliatedStudents::
                            where('student_company_id', $userStudent->affiliated_company->where(
                                'rol_id', 1
                            )->where('company_id', 1)->first()->id)->update(array(
                                'age_stage' => $request->kidSelected,
                            ));
                        }
                        $userStudent->save();
                        return response()->json(['data' => $userStudent], 200);
                    }
                    return response()->json(['data' => '', 'message' => 'El usario ha editar no se encuentra'], 200);
                }
                return response()->json(['data' => '', 'message' => 'No tiene permiso para realizar esta acción'], 200);
            } else {
                $userStudent = AfiliadoEmpresa::find(auth('afiliadoempresa')->user()->id);
                if ($userStudent->exists()) {
                    $student['name'] = $userStudent->name;
                    $student['last_name'] = $userStudent->last_name;
                    $dataArray = array();
                    if($userStudent->name != $request->name){
                        $userStudent->name = $request->name;
                        $dataToChange['key'] = 'Nombre';
                        $dataToChange['value'] = $request->name;
                        array_push($dataArray,$dataToChange);
                    }
                    if($userStudent->last_name != $request->last_name){
                        $userStudent->last_name = $request->last_name;
                        $dataToChange['key'] = 'Apellido';
                        $dataToChange['value'] = $request->last_name;
                        array_push($dataArray,$dataToChange);
                    }
                    if (isset($request->user_name)) {
                        $exist = AfiliadoEmpresa::where('user_name', $request->user_name)->first();
                        if ($exist === null) {
                            $userStudent->user_name = $request->user_name;
                            $dataToChange['user_name'] = $request->user_name;
                            $dataToChange['key'] = 'Usuario';
                            $dataToChange['value'] = $request->user_name;
                            array_push($dataArray,$dataToChange);
                        } else {
                            return response()->json(['data' => '', 'message', 'El nombre de usuario ya esta en uso'], 200);
                        }
                    }
                    //if (isset($request->birthday)) {
                        //$userStudent->birthday != $request->birthday?$dataToChange['birthday'] = $request->birthday:'';
                        if($userStudent->birthday != $request->birthday){
                            $userStudent->birthday = $request->birthday;
                            $dataToChange['key'] = 'Fecha de nacimiento';
                            $dataToChange['value'] = $request->birthday;
                            array_push($dataArray,$dataToChange);
                        }
                    //}
                    if (isset($request->password)) {
                        $userStudent->password = Hash::make($request->password);
                        $dataToChange['key'] = 'Contraseña';
                        $dataToChange['value'] = $request->password ;
                        array_push($dataArray,$dataToChange);
                        $request->session()->put([
                            'password_hash' => $userStudent->password,
                        ]);

                    }
                    //if (isset($request->kidSelected)) {
                        if($userStudent->kidSelected() != $request->kidSelected){
                            $dataToChange['age_stage'] = $request->kidSelected;
                            $dataToChange['key'] = 'Etapa de edad';
                            $dataToChange['value'] = $request->kidSelected ;
                            array_push($dataArray,$dataToChange);
                            ConectionAffiliatedStudents::
                            where('student_company_id', $userStudent->affiliated_company->where(
                                'rol_id', 1
                            )->where('company_id', 1)->first()->id)->update(array(
                                'age_stage' => $request->kidSelected,
                            ));
                        }

                    //}
                    $userStudent->save();
                    $tutor = AfiliadoEmpresa::whereHas('affiliated_company', function ($query) use ($request, $userStudent) {
                        $query->whereHas('conection_tutor', function ($query) use ($userStudent, $request) {
                            $query->where('student_company_id', $userStudent->affiliated_company->where('rol_id', 1)->where('company_id', 1)->first()->id);
                        })->where([
                            ['rol_id', 3],
                            ['company_id', 1]
                        ]);
                    })->first();

                    Mail::to($tutor->email)->send(new SendChangesProfileStudent($dataArray,$tutor,$student));
                    return response()->json(['data' => $userStudent], 200);
                }
                return response()->json(['data' => '', 'message' => 'El usario ha editar no se encuentra'], 200);
            }
        }
        return response()->json(['data' => '', 'message' => 'No tiene una sesión activa'], 200);

    }

    /**
     * @param Request $request
     * @param $userName
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate_user_name(Request $request, $userName)
    {

        $exist = AfiliadoEmpresa::where('user_name', $userName)->get();
        if (count($exist)) {
            return response()->json(['data' => false], 200);
        } else {
            return response()->json(['data' => true], 200);
        }
    }

    public function user_mail_validation(Request $request){
        if(auth('afiliadoempresa')->user()->confirm_email){
            return response()->json(['status' => 'successfull', 'message' => 'El usario ha validado el correo'], 200);
        }
        else{
            try {
                Mail::to(auth('afiliadoempresa')->user()->email)->send(
                    new SendConfirmMailNotification());
            } catch (\Exception $ex) {

                return response()->json(['status' => 'unsuccessfull', 'message' => 'no se ha podido enviar el correo'], 200);
            }
            return response()->json(['status' => 'unsuccessfull', 'message' => 'El usuario no ha validado el correo'], 200);
        }

    }

    public function confirm_mail (Request $request, $userId){

       $user =  AfiliadoEmpresa::where('id',$userId)->first();

        if($user != null){
            $user->confirm_email = true;
            $user->save();
            return redirect()->route('shoppingCart');
        }


    }

}
