<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlanCompletion extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $nameFamiliar;
    public $student;
    public $sequence;
    public $plan;
    public function __construct($nameFamiliar,$student,$sequence,$plan)
    {
        //
        $this->nameFamiliar =$nameFamiliar;
        $this->student =$student;
        $this->sequence =$sequence;
        $this->plan =$plan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');
        return
            $this->from(env('EMAIL_OPERATION'))
                ->markdown('vendor.notifications.planCompletion')
                ->subject('Conexiones - Finalización contenido');
    }
}
