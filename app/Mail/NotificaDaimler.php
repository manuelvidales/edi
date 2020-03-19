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
    public $id;
    public $origen;
    public $destino;
    public $fecha;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $origen, $destino, $fecha)
    {
        $this->id = $id;
        $this->origen = $origen;
        $this->destino = $destino;
        $this->fecha = $fecha;
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
