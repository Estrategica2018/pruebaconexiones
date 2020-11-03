<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PaymentConfirmation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:confirm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Query the payment in pending status and send search in mercadopago';

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
        /*Lanza tarea de consulta de notificaciones pendientes*/
        app(\App\Http\Controllers\Payment\PaymentConfirmationController::class)->payment_confirmation();
    }
}
