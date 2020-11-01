<?php

namespace App\Http\Controllers\Admin;

use App\fileUpload;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FileUploadLogsController extends Controller
{
    private $resultsDirectory;

    public function __construct()
    {
        $this->resultsDirectory = public_path() . "/fileUploadDirectory/results/";
    }

    public function index(Request $request)
    {
        if ($request->resultFile) {
            $resultData = [];
            array_push($resultData, $this->parseResult($request->resultFile));
            return view('roles.admin.fileUploadLogs', ['resultData' => $resultData]);
        } else {
            $resultData = [];
            $filesNames = $this->scan_dir($this->resultsDirectory);
            
            for ($i = 0; $i < count($filesNames); ++$i) {
                if ($filesNames[$i] !== '.' && $filesNames[$i] !== '..') {
                    array_push($resultData, $this->parseResult($filesNames[$i]));
                }
            }
            return view('roles.admin.fileUploadLogs', ['resultData' => $resultData]);
        }
    }

    private function parseResult($resultFileName)
    {


        $resultData = [];
        $resultData["errors"] = [];

        $myfile = fopen($this->resultsDirectory . $resultFileName, "r"); //-->read only

        //Output lines until EOF is reached
        while (!feof($myfile)) {
            $line = fgets($myfile);
            if (strpos($line, "initProcess ->") === 0) {
                $resultData["initProcess"] = explode("initProcess ->", $line)[1];
            } else if (strpos($line, "fileName ->") === 0) {
                $resultData["fileName"] = explode("fileName ->", $line)[1];

            } else if (strpos($line, "fileSize ->") === 0) {
                $resultData["fileSize"] = explode("fileSize ->", $line)[1];
            } else if (strpos($line, "Error ->") === 0) {
                array_push($resultData["errors"], explode("Error ->", $line)[1]);
            } else if (strpos($line, "successfullRecords ->") === 0) {
                $resultData["successfullRecords"] = explode("successfullRecords ->", $line)[1];
            } else if (strpos($line, "companyName ->") === 0) {
                $resultData["companyName"] = explode("companyName ->", $line)[1];
            } else if (strpos($line, "sequenceName ->") === 0) {
                $resultData["sequenceName"] = explode("sequenceName ->", $line)[1];
            } else if (strpos($line, "gradeName ->") === 0) {
                $resultData["gradeName"] = explode("gradeName ->", $line)[1];
            } else if (strpos($line, "teacherName ->") === 0) {
                $resultData["teacherName"] = explode("teacherName ->", $line)[1];
            } else if (strpos($line, "errorRecords ->") === 0) {
                $resultData["errorRecords"] = explode("errorRecords ->", $line)[1];
            } else if (strpos($line, "total ->") === 0) {
                $resultData["total"] = explode("total ->", $line)[1];
            }
        }
        fclose($myfile);

        return $resultData;
    }
    
    function scan_dir($dir) {
        $ignored = array('.', '..', '.svn', '.htaccess');

        $files = array();    
        foreach (scandir($dir) as $file) {
            if (in_array($file, $ignored)) continue;
            $files[$file] = filemtime($dir . '/' . $file);
        }

        arsort($files);
        $files = array_keys($files);

        return ($files) ? $files : false;
    }
}
