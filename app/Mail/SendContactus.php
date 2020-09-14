<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendContactus extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $data;

    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->data['user_notification'] == 1) {
            return
                $this->from($this->data['email'])
                    ->markdown('vendor.notifications.registerContactus', ['data' => $this->data])
                    ->subject('Conexiones - Notificación contáctenos');
        }
        return
            $this->from('contacto@educonexiones.com')
                ->markdown('vendor.notifications.registerContactus', ['data' => $this->data])
                ->subject('Conexiones - Notificación contáctenos');
    }
}
