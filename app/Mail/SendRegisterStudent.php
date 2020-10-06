<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRegisterStudent extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $student;
    private $family;

    public function __construct($student, $family)
    {
        //
        $this->student = $student;
        $this->family = $family;

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
                ->markdown('vendor.notifications.registerStudent', ['family' => $this->family, 'student' => $this->student])
                ->subject('Conexiones - Registro estudiante');

    }
}
