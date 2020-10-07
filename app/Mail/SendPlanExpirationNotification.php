<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPlanExpirationNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;
    public $sequence='';
    public function __construct($user)
    {
        //
        $this->data = $user;
        foreach($this->data->affiliated_content_account_service as $relationSequence){
            $this->sequence = $this->sequence.','.$relationSequence->sequence->name;
        }


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return
            $this->from(env('EMAIL_OPERATION'))
                ->markdown('vendor.notifications.sendPlanExpirationNotification')
                ->subject('Conexiones - Notificación fecha de expiración de planes');
    }
}
