<?php

namespace App\Http\Controllers\corpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\CorpoImport;
use Maatwebsite\Excel\Excel;
use App\Models\Corpo;

class CorpoboyacaController extends Controller
{
    public function show(Request $request) {
        return view('corpo.upload');
    }
    
    public function upload(Request $request) {
        $result = [];
        $result['initProcess'] = date("Y-m-d H:i:s");
        $fileInput = $_FILES['fileInput'];
        $uploadDirectory = public_path() . "/corpo/pendings/";
        $processedDirectory = public_path() . "/corpo/processed/";
        $resultsDirectory = public_path() . "/corpo/results/";

        if ($fileInput && $fileInput['error'] == '0') {
            $fileName = $fileInput['name'];
            $fileSize = $fileInput['size'];
            $fileType = $fileInput['type'];
            
            if (move_uploaded_file($_FILES['fileInput']['tmp_name'], $uploadDirectory . $fileName)) {
                
                $date = date("Ymd_His");
                $resultFile = $fileName .'_'. $date .'.info';
                $resultFilePath = $resultsDirectory . '/' . $resultFile;
                $myfile = fopen($resultFilePath, "w");

                //invoca la carga del archivo a la base de datos
                $import = new CorpoImport($request, $resultFilePath);
                $import->import($uploadDirectory . $fileName, null, Excel::XLS);
                
                $result['fileName'] = $fileName;
                $result['fileSize'] = $fileSize;
                $result['fileType'] = $fileType;
                $result['successfullRecords'] = $import->getRowCount() - 1;
                $result['errorRecords'] = $import->getErrorCount();
                $result['total'] = $import->getErrorCount() + $import->getRowCount() - 1;
                $result['endProcess'] = date("Y-m-d H:i:s");
                return view('corpo.upload', ['result' => $result]);

            } else {
                echo "¡Posible ataque de subida de ficheros!\n";
            }
        } else {
            echo "¡Error cargando el fichero!\n";
            print_r($fileInput);
        }
    }
    
    public function search(Request $request)
    {
        if(isset($request->doc_number) && isset($request->code_in) && $request->doc_number != 'NoAply' && $request->code_in != 'NoAply') {
            $corpo = Corpo::
			whereRaw('LPAD(code_in,7,"0") = ?', array($request->code_in) )
			->where('request_at', 'like', $request->year+'%')
			->where('remetente_doc_number', $request->doc_number )->get();
        }
        else if(isset($request->code_in) && $request->code_in != 'NoAply' ) {
            $corpo = Corpo::whereRaw('LPAD(code_in,7,"0") = ?', array($request->code_in) )
			->where('request_at', 'like', $request->year . '%')
			->get();
        }
        else {
             $corpo = Corpo::where('remetente_doc_number', $request->doc_number )
			 ->where('request_at', 'like', $request->year . '%')
			 ->get();
        }
        
        return response()->json([
            'status' => 'successfull',
            'message' => 'Datos consultados',
            'data' => $corpo
        ]);
    }
}
