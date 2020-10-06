<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendChangesProfileStudent extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $data;
    private $family;
    private $student;

    public function __construct($data,$family,$student)
    {
        //
        $this->data = $data;
        $this->family = $family;
        $this->student = $student;

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
                ->markdown('vendor.notifications.changesProfileStudent',
                    ['data' => $this->data,'family' => $this->family,'student'=>$this->student])
                ->subject('Educonexiones - Notificación de actulización rol estudiante');

    }
}
