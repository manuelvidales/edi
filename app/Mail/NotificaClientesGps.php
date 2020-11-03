<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificaClientesGps extends Mailable
{
    use Queueable, SerializesModels;

    public $viaje;
    public $operador;
    public $unidad;
    public $placas;
    public $remolque;
    public $cliente;
    public $ruta;
    public $origen;
    public $destino;
    public $status;
    public $fecha;
    public $lat;
    public $lon;
    public $address;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($viaje, $operador, $unidad, $placas, $remolque, $cliente, $ruta, $origen, $destino, $status, $fecha, $lat, $lon, $address)
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
        $this->status = $status;
        $this->fecha = $fecha;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->address = $address;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('BITACORA DE ESTATUS DEL VIAJE: '.$this->viaje);
        return $this->view('mails.clientesgps');
    }
}
