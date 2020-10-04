<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackUpGoogleDrive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create daily backups in google drive account';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        app(\App\Http\Controllers\BackupController::class)->mysqlDump();
        app(\App\Http\Controllers\BackupController::class)->imageFolder();
    }
}
