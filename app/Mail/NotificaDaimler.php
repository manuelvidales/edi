<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificaDaimler extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Notificacion Daimler';
    public $code;
    public $id;
    public $origen;
    public $destino;
    public $fecha;
    public $hora;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code, $id, $origen, $destino, $fecha, $hora)
    {
        $this->code = $code;
        $this->id = $id;
        $this->origen = $origen;
        $this->destino = $destino;
        $this->fecha = $fecha;
        $this->hora = $hora;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.daimler');
    }
}
