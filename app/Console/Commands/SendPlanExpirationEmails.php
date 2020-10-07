<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AffiliatedAccountService;
use App\Mail\SendPlanExpirationNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendPlanExpirationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:planExpiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification the next expiration of the rating plan';

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
        $expiration_date = Carbon::now()->addDays(4)->format('Y-m-d');
        
        $users = AffiliatedAccountService::with('rating_plan','company_affiliated.retrive_afiliado_empresa','affiliated_content_account_service.sequence')
        ->where('end_date',$expiration_date)->each(function ($user) {
            try {
                $email_to = env('APP_ENV') == 'production' ? 
                    $user->company_affiliated->retrive_afiliado_empresa->email :
                    $user->company_affiliated->retrive_afiliado_empresa->email.'prueba';
					
                Mail::to($email_to)->send(new SendPlanExpirationNotification($user));

            } catch (\Exception $ex) {
                return $ex;
            }
            //dd($user->company_affiliated->retrive_afiliado_empresa->email); 
            //dd($user->rating_plan->name, $user->rating_plan->type_rating_plan_id);
            //dd($user->affiliated_content_account_service[0]->sequence);
        });
    }
}
