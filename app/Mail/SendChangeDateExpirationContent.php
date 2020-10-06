<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendChangeDateExpirationContent extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $student;
    private $family;
    private $originalEndDate;
    private $end_date;
    private $plan;
    private $full_name;

    public function __construct($originalEndDate, $end_date, $plan, $full_name)
    {
        //
        $this->originalEndDate = $originalEndDate;
        $this->end_date = $end_date;
        $this->plan = $plan;
        $this->full_name = $full_name;

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
                ->markdown('vendor.notifications.changeDateExpirationContent',
                    [
                        'originalEndDate' => $this->originalEndDate,
                        'end_date' => $this->end_date,
                        'plan' => $this->plan,
                        'full_name' => $this->full_name,
                    ])
                ->subject('Conexiones - Ampliación fecha de expiración');

    }
}
