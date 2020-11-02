<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificaClientes extends Mailable
{
    use Queueable, SerializesModels;

    // public $subject = 'BITACORA DE ESTATUS DE VIAJE';
    public $viaje;
    public $operador;
    public $unidad;
    public $placas;
    public $remolque;
    public $cliente;
    public $ruta;
    public $origen;
    public $destino;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($viaje, $operador, $unidad, $placas, $remolque, $cliente, $ruta, $origen, $destino)
    {
        $this->viaje = $viaje;
        $this->operador = $operador;
        $this->unidad = $unidad;
        $this->placas = $placas;
        $this->remolque = $remolque;
        $this->cliente = $cliente;
        $this->ruta = $ruta;
        $this->origen = $origen;
        $this->destino = $destino;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('BITACORA DE ESTATUS DEL VIAJE: '.$this->viaje);
        return $this->view('mails.clientes');
    }
}
