<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificaClientes;
use App\Mail\messagesend;

class clientes extends Model
{
    public function statusViajes ($value){

        $viaje = $value->no_viaje;
        $operador = $value->operador;
        $unidad = $value->unidad;
        $placas = $value->placas;
        $remolque = $value->remolque;
        $cliente = $value->cliente;
        $ruta = $value->ruta;
        $origen = $value->origen;
        $destino = $value->destino;
        $status = $value->status;
        $fecha = $value->fecha;

            // consultar correos del cliente
            $sql = DB::connection('sqlsrvpro')->table("bitacora_clientes_correos")->select('correo')->where('id_cliente','=', $value->id_cliente)->get();
            if (count($sql) !== 0) {
                for($i = 0; $i<count($sql); $i++){
                    //notificar al correo del cliente
                    Mail::to($sql[$i]->correo)->send(new NotificaClientes($viaje, $operador, $unidad, $placas, $remolque, $cliente, $ruta, $origen, $destino, $status, $fecha));
                    Log::info('Correo enviado!');
                }
                //update tabla send_txt a 0 terminar el proceso
                if (DB::connection('sqlsrvpro')->table("bitacora_clientes")->where([ ['id_incremental', '=', $value->id_incremental] ])->update(['send_txt' => '0'])) {
                    Log::info('tabla bitacora_clientes actualiza!');
                } else {
                    Log::error('No se pudo Actualizar tabla: bitacora_clientes');
                }
            } else {
                Log::alert('No existe correo cliente id: '.$value->id_cliente);
                // informar por correo al trafico ace PENDIENTE
                Mail::to(env('MAIL_CLIENTES'))->send(new messagesend($cliente));
                Log::info('Correo enviado, falta de correo!');

                //update tabla send_txt a 2 Falta de correo
                if (DB::connection('sqlsrvpro')->table("bitacora_clientes")->where([ ['id_incremental', '=', $value->id_incremental] ])->update(['send_txt' => '2'])) {
                    Log::info('Actualiza send_txt = 2 falta de correo');
                }
            }

        }

    public function statusViajeGps ($value){

    }
}
