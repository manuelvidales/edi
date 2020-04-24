<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificaVisteon extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Notificacion Halcon';
    public $id;
    public $fecha;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $fecha)
    {
        $this->id = $id;
        $this->fecha = $fecha;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.visteon');
    }
}
