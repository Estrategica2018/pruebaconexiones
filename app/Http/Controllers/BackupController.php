<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ifsnop\Mysqldump as IMysqldump;
use File;
use Storage;

class BackupController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function mysqlDump()
    {
        try {
            
            File::isDirectory(public_path() .'/backups/work') or File::makeDirectory(public_path() .'/backups/work', 0777, true, true);
            $strDate = date("YmdHis");
            $db_host = env('DB_HOST');
            $db_database = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $dump = new IMysqldump\Mysqldump('mysql:host='.$db_host.';dbname='.$db_database, $db_username, $db_password);
            $sqlFile = public_path() . '/backups/work/dump_'.$strDate.'.sql';
            $dump->start($sqlFile);
            
            
            Storage::cloud()->put('dump_'.$strDate.'.sql', 
                file_get_contents($sqlFile));
            
            $filePath = public_path() .'/backups/logs';
            $filename = $filePath . '/log_'.$strDate.'.txt';
            $this->writeLog($filename,'finaliza mysqldump-php');
            
        } catch (\Exception $e) {
            echo 'mysqldump-php error: ' . $e->getMessage();
        }
    }
    
    function getDirContents($dir, &$results = array()) {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                $results[] = $path;
            }
        }
        return $results;
    }

    public function imageFolder() {
        // Enter the name of directory 
        $pathdir = public_path() . '/images/designerAdmin/';
        // Enter the name to creating zipped directory 
        $strDate = date('YmdHis');
        $zipcreated = public_path() . '/backups/work/designerAdmin_'.$strDate.'.zip'; 
        // Create new zip class 
        $zip = new \ZipArchive(); 
        
        if($zip -> open($zipcreated, \ZipArchive::CREATE ) === TRUE) {
            $files = $this->getDirContents($pathdir);
            $ix = 0;
            foreach($files as $file) { 
                if(is_file($file)) {
                    $fileName = (str_replace(str_replace('\\','/',$pathdir),'',str_replace('\\','/',$file)));
                    $zip->addFile($file, $fileName);
                    $ix = $ix  + 1;
                }
            }
            $zip->close(); 
        }
        
        Storage::cloud()->put('designerAdmin_'.$strDate.'.zip', file_get_contents($zipcreated));
    }
    
    public function writeLog($filename, $string) {

        if (!file_exists($filename)) {
            touch($filename, strtotime('-1 days'));
        }
        $strDate = date("[Y-m-d H:i:s]");
        file_put_contents($filename, $strDate . ' '.$string . PHP_EOL, FILE_APPEND);
    }
}
