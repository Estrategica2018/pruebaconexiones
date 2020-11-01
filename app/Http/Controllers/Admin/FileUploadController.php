<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class FileUploadController extends Controller
{
    //
    public function index(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['admin']);
        return view('roles.admin.fileUpload');
    }

    public function store(Request $request)
    {
        $request->user('afiliadoempresa')->authorizeRoles(['admin']);

        $fileInput = $_FILES['fileInput'];
        $uploadDirectory = public_path() . "/fileUploadDirectory/pendings/";
        $processedDirectory = public_path() . "/fileUploadDirectory/processed/";
        $resultsDirectory = public_path() . "/fileUploadDirectory/results/";

        if ($fileInput && $fileInput['error'] == '0') {
            $fileName = $fileInput['name'];
            $fileSize = $fileInput['size'];
            $fileType = $fileInput['type'];
            $companyName = $request->company_name;
            $sequenceName = $request->sequence_name;
            $gradeName = $request->group_name;
            $teacherName = $request->teacher_name;

            //if (move_uploaded_file($fileInput['tmp_name'], $uploadDirectory . $fileName)) {
            if (move_uploaded_file($_FILES['fileInput']['tmp_name'], $uploadDirectory . $fileName)) {
                $date = date("Ymd_His");
                $resultFile = $fileName .'_'. $date .'.info';
                $resultFilePath = $resultsDirectory . '/' . $resultFile;
                $myfile = fopen($resultFilePath, "w");

                //dd( $fileName, $companyName, $teacherName);
                //invoca la carga del archivo a la base de datos

                $import = new UsersImport($request, $resultFilePath);
                ($import)->import($uploadDirectory . $fileName, null, Excel::XLS);
                //dd($import->getRowCount());
                $myfile = fopen($resultFilePath, "a+");

                fwrite($myfile, "fileName -> " . $fileName . "\n");
                fwrite($myfile, "fileSize -> " . $fileSize . "\n");
                fwrite($myfile, "fileType -> " . $fileType . "\n");
                fwrite($myfile, "companyName -> " . $companyName . "\n");
                fwrite($myfile, "sequenceName -> " . $sequenceName . "\n");
                fwrite($myfile, "gradeName -> " . $gradeName . "\n");
                fwrite($myfile, "teacherName -> " . $teacherName . "\n");
                fwrite($myfile, "successfullRecords -> " . ($import->getRowCount() - 1)  . "\n");
                fwrite($myfile, "errorRecords -> " . $import->getErrorCount() . "\n");
                fwrite($myfile, "total -> " . ($import->getErrorCount() + $import->getRowCount() - 1) . "\n");

                fwrite($myfile, "\n");
                fwrite($myfile, "initProcess -> " . date("Y-m-d H:i:s") . "\n");
                fclose($myfile);

                return redirect()->action('Admin\FileUploadLogsController@index', [
                    'resultFile' => $resultFile,
                ]);

            } else {
                echo "¡Posible ataque de subida de ficheros!\n";
            }
        } else {
            echo "¡Error cargando el fichero!\n";
        }

    }

}
