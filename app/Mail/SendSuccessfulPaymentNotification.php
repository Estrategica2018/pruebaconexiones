<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendSuccessfulPaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $shoppingCart;
    private $request;
    private $afiliadoEmpresa;
    private $price_callback;
    private $transaction_date;

    public function __construct($shoppingCart, $request, $afiliadoEmpresa, $price_callback, $transaction_date)
    {
        //
        $this->shoppingCart = $shoppingCart;
        $this->request = $request;
        $this->afiliadoEmpresa = $afiliadoEmpresa;
        $this->price_callback = $price_callback;
        $this->transaction_date = $transaction_date;

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
                ->markdown('vendor.notifications.successfulPaymentNotification',
                    ['shoppingCart' => $this->shoppingCart,
                        'request' => $this->request,
                        'afiliadoEmpresa' => $this->afiliadoEmpresa,
                        'price_callback' => $this->price_callback,
                        'transaction_date' => $this->transaction_date
                    ])
                ->subject('Educonexiones - Notificación de pago exitoso');

    }
}
