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
            
            //Consulta la carpeta backup en el Drive
            $folderName = date('Ymd');
            $contents = collect(Storage::cloud()->listContents('/', false));
            $dir = $contents->where('type', '=', 'dir')
                ->where('filename', '=', $folderName)
                ->first(); // There could be duplicate directory names!
            if ( ! $dir) {
                dd('error consultando carpeta backup');
            }
            
            $strDate = date('YmdHis');
            $backupDirectory = public_path() .'/backups/work/'.$strDate.'/';
            File::isDirectory($backupDirectory) or File::makeDirectory($backupDirectory, 0777, true, true);
            
            $db_host = env('DB_HOST');
            $db_database = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $dump = new IMysqldump\Mysqldump('mysql:host='.$db_host.';dbname='.$db_database, $db_username, $db_password);
            $sqlFile = $backupDirectory.'/dump_'.$strDate.'.sql';
            $dump->start($sqlFile);
            
            Storage::cloud()->put($dir['path'].'/dump_'.$strDate.'.sql', 
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
        
        //Consulta la carpeta backup en el Drive
        $folderName = date('Ymd');
        $contents = collect(Storage::cloud()->listContents('/', false));
        $dir = $contents->where('type', '=', 'dir')
            ->where('filename', '=', $folderName)
            ->first(); // There could be duplicate directory names!
        if ( ! $dir) {
            dd('error consultando carpeta backup');
        }
        

        // Enter the name to creating zipped directory 
        $pathdir = env('ADMIN_DESIGN_PATH').'/';
        
        $strDate = date('YmdHis');
        $backupDirectory = public_path() .'/backups/work/'.$strDate.'/';
        File::isDirectory($backupDirectory) or File::makeDirectory($backupDirectory, 0777, true, true);
        
        $zipcreated = $backupDirectory.'/designerAdmin_'.$strDate.'.zip'; 
        // Create new zip class 
        $zip = new \ZipArchive(); 
        
        if($zip -> open($zipcreated, \ZipArchive::CREATE ) === TRUE) {
            $files = $this->getDirContents($pathdir);
            $ix = 1;
            foreach($files as $file) {
                if($ix % 50 == 0) {
                    $zip->close();
                    $zipcreated = $backupDirectory.'/designerAdmin_'.$strDate.'-'.$ix.'.zip'; 
                    // Create new zip class 
                    $zip = new \ZipArchive();
                    if($zip -> open($zipcreated, \ZipArchive::CREATE ) === FALSE) {
                        dd('error');
                    }
                }
                if(is_file($file)) {
                    $fileName = (str_replace(str_replace('\\','/',$pathdir),'',str_replace('\\','/',$file)));
                    print_r($fileName.'<br>');
                    $zip->addFile($file, $fileName);
                    $ix = $ix  + 1;
                }
            }
            $zip->close();
        }
        
        $filesZipped = $this->getDirContents($backupDirectory);
        $indx = 1;
        foreach($filesZipped as $file) {
            Storage::cloud()->put($dir['path'].'/designerAdmin_'.$strDate.'-'.$indx.'.zip', file_get_contents($file));
            $indx = $indx + 1 ;
        }
    }
    
    public function writeLog($filename, $string) {

        if (!file_exists($filename)) {
            touch($filename, strtotime('-1 days'));
        }
        $strDate = date("[Y-m-d H:i:s]");
        file_put_contents($filename, $strDate . ' '.$string . PHP_EOL, FILE_APPEND);
    }
}
