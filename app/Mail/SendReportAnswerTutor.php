<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReportAnswerTutor extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $student;
    private $family;
    private $data;
    private $sequence;
    private $moment;
    private $level;
    private $performance_comment;
    private $color_performance;
    private $performance;
    private $place_advance_line;

    public function __construct($family, $student, $data, $sequence, $moment, $level, $performance_comment, $color_performance,$performance, $place_advance_line)
    {
        //
        $this->family = $family;
        $this->student = $student;
        $this->data = $data;
        $this->sequence = $sequence;
        $this->moment = $moment;
        $this->level = $level;
        $this->performance_comment = $performance_comment;
        $this->color_performance = $color_performance;
        $this->performance = $performance;
        $this->place_advance_line = $place_advance_line;


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
                ->markdown('vendor.notifications.reportStudentAnswer',
                    [
                        'family' => $this->family,
                        'student' => $this->student,
                        'data' => $this->data,
                        'moment' => $this->moment,
                        'sequence' => $this->sequence,
                        'level' => $this->level,
                        'performance_comment' => $this->performance_comment,
                        'color_performance' => $this->color_performance,
                        'performance' => $this->performance,
                        'place_advance_line' => $this->place_advance_line,

                    ])
                ->subject('Conexiones - Reporte estudiante');

    }
}