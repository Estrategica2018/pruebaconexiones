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
    public function mysqlDump(Request $request)
    {
        try {
			$dump = new IMysqldump\Mysqldump('mysql:host=localhost;dbname=proyectoeducativo', 'root', '');
			
			$db_host = env('DB_HOST');
			$db_database = env('DB_DATABASE');
			$db_username = env('DB_USERNAME');
			$db_password = env('DB_PASSWORD');
			$dump = new IMysqldump\Mysqldump('mysql:host='.$db_host.';dbname='.$db_database, $db_username, $db_password);
			$strDate = date("YmdHis");
			File::isDirectory(public_path() .'/backups/work') or File::makeDirectory(public_path() .'/backups/work', 0777, true, true);
			$dump->start(public_path() . '/backups/work/dump_'.$strDate.'.sql');
			echo 'mysqldump-php sucess';
		} catch (\Exception $e) {
			echo 'mysqldump-php error: ' . $e->getMessage();
		}
    }
}
