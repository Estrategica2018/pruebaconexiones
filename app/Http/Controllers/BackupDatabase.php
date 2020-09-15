<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ifsnop\Mysqldump as IMysqldump;
use File;

class BackupDatabase extends Controller
{
    /**
     * 
     *
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
            $dump->start(public_path() . '/backups/work/dump_'.$strDate.'.sql');
			
            $filePath = public_path() .'/backups/logs';
            $filename = $filePath . '/log_'.$strDate.'.txt';
            $this->writeLog($filename,'finaliza mysqldump-php');

        } catch (\Exception $e) {
            echo 'mysqldump-php error: ' . $e->getMessage();
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
