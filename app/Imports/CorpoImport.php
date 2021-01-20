<?php

namespace App\Imports;

use App\Models\Corpo;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Hash;

class CorpoImport implements ToModel, WithValidation, SkipsOnFailure {
    use Importable, SkipsFailures;

    private $request;
    private $resultFile;
    private $rows = 0;
    private $errors= 0;

    public function __construct($request, $resultFile) {
        $this->request = $request;
        $this->resultFile = $resultFile;
    }

    public function model(array $row) {
        ++$this->rows;
        if($this->rows  >  3) {
            $id = $row[0];
            $register = Corpo::where('id',$id)->first();
            if($register == null) {
                $register = new Corpo();
                $register->id = $id;
            }

            $register->code_in = $row[3];
            $register->request_at = $row[4] . ' : '.$row[5];
            $register->status = $row[7];
            $register->response_date = $row[11];
            $register->response_hour = $row[12];
            $register->code_out = $row[17];
            $register->remetente_name = $row[30];
            $register->remetente_doc_type = $row[31];
            $register->remetente_doc_number = $row[32];
            $register->remetente_email = $row[33];
            //$register->serie = $row[21];
            $register->subject_to = $row[45];
			$register->serie = $row[46];
            
            $register->save();
            DB::commit();
            return $register;
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
            
        ];
    }

    public function customValidationAttributes()
    {
        return [
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
