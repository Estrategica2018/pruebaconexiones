<?php

namespace App\Imports;

use App\Models\AffiliatedCompanyRole;
use App\Models\AfiliadoEmpresa;
use App\Models\CompanyAffiliatedAssignmentUser;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToModel, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $request;
    private $resultFile;
    private $rows = 0;
    private $errors= 0;

    public function __construct($request, $resultFile)
    {
        $this->request = $request;
        $this->resultFile = $resultFile;
        //dd($request);
    }

    public function model(array $row)
    {
        ++$this->rows;
        
        if($this->rows>1) {
            $user = AfiliadoEmpresa::where('email')->first();
            if($user == null) {
                $user = new AfiliadoEmpresa();
            }
            $user->user_name = $row[0];
            $user->name = $row[1];
            $user->last_name = $row[2];
            $user->email = $row[3];
            $user->save();
            DB::commit();
            
            $userRole = AffiliatedCompanyRole::
                where([['affiliated_company_id',$user->id],
                       ['company_id',$this->request->companySelect],
                       ['rol_id',1]])->first();
            if($userRole == null) {
                $userRole = new AffiliatedCompanyRole();
                $userRole->affiliated_company_id = $user->id;
                $userRole->rol_id = 1;
                $userRole->company_id = $this->request->companySelect;
                $userRole->save();
                DB::commit();
            }
            $userAssigment = CompanyAffiliatedAssignmentUser::
                where([['student_company_id',$userRole->id],
                       ['teacher_company_id',$this->request->teacherSelect],
                       ['company_sequence_id',$this->request->sequenceSelect],
                       ['company_group_id',$this->request->groupSelect]])->first();
                       
            if($userAssigment == null) {
                $userAssigment = new CompanyAffiliatedAssignmentUser();
            }
            
            $userAssigment->student_company_id = $userRole->id;
            $userAssigment->teacher_company_id = $this->request->teacherSelect;
            $userAssigment->company_sequence_id = $this->request->sequenceSelect;
            $userAssigment->company_group_id = $this->request->groupSelect;
            $userAssigment->save();
            DB::commit();
            return $userAssigment;
        }
    }

    public function onFailure(Failure...$failures)
    {
        // Handle the failures how you'd like.
        foreach ($failures as $failure) {
            ++$this->errors;
            $failure->row(); // row that went wrong
            $failure->attribute(); // either heading key (if using heading row concern) or column index
            $failure->errors(); // Actual error messages from Laravel validator
            $failure->values(); // The values of the row that has failed.
            $myfile = fopen($this->resultFile, "a+");
            fwrite($myfile, "Error -> Linea: " . $failure->row() . " Causa: " . ' ' . $failure->attribute());
            fwrite($myfile, "\n");
            fclose($myfile);
        }
    }

    public function rules(): array
    {
        return [
            '0' => 'required',
            '1' => 'required',
            '2' => 'required',
            '3' => 'required',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '0' => 'Usuario vacio para la columna Usuario',
            '1' => 'Usuario vacio para la columna Nombre',
            '2' => 'Usuario vacio para la columna Apellido',
            '3' => 'Usuario vacio para la columna Email',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getErrorCount(): int
    {
        return $this->errors;
    }

    
}
