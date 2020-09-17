<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use File;
use Artisan ;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $filePath = public_path() .'/backups/logs';
        File::isDirectory($filePath) or File::makeDirectory($filePath, 0777, true, true);
        $strDate = date("YmdHis");
        
        $filename = $filePath . '/log_'.$strDate.'.txt';
        $this->writeLog($filename,'inicia crontab');
        
        //$schedule->call('App\Http\Controllers\BackupDatabase@mysqlDump')->everyMinute()->appendOutputTo(storage_path('logs/examplecommand.log'));
        //app(\App\Http\Controllers\BackupDatabase::class)->mysqlDump();
        
        
        /*Lanza tarea de consulta de notificaciones pendientes*/
        app(\App\Http\Controllers\Payment\PaymentConfirmationController::class)->payment_confirmation();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    
    public function writeLog($filename, $string) {

        if (!file_exists($filename)) {
            touch($filename, strtotime('-1 days'));
        }
        $strDate = date("[Y-m-d H:i:s]");
        file_put_contents($filename, $strDate . ' '.$string . PHP_EOL, FILE_APPEND);
    }
}
