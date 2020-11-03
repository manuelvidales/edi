<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificaClientes;
use App\Mail\NotificaClientesGps;
use App\Mail\messagesend;

class clientes extends Model
{
    public function statusData($value, $id) {

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
        $idcliente = $value->id_cliente;

        // consultar correos del cliente
        $sql = DB::connection('sqlsrvpro')->table("bitacora_clientes_correos")->select('correo')->where('id_cliente','=', $value->id_cliente)->get();

        // identificar el tipo de estatus para viajes = 1 & gps =2
        if ($id == 1) {
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

                $error = new clientes();
                $error->errorCorreo($cliente, $idcliente);

                //update tabla send_txt a 2 Falta de correo
                if (DB::connection('sqlsrvpro')->table("bitacora_clientes")->where([ ['id_incremental', '=', $value->id_incremental] ])->update(['send_txt' => '2'])) {
                    Log::info('Actualiza send_txt = 2 falta de correo');
                }
            }
        } else { // Para los estatus de GPS

            //preparar conexion con el Webservice
            $server = file_get_contents(env('WEB_SERVICE_GPS'));
            $file_headers = @get_headers($server);

            //validar webservice en linea
            if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                Log::error('Web Service OffLine');
            } else {

                // consultar por numero de unidad
                $webservice = json_decode( file_get_contents(env('WEB_SERVICE_GPS').'/events/Data.jsonx?d='.$value->unidad.'&a=autofleteshalcon&u=web&p=web123&l=1'), true );
                if (count($webservice) == 4) {

                    // obetener Latitud y longuitud
                    $listjson = $webservice['DeviceList'];
                    $datajson = $listjson[0]['EventData'];
                    $lat = $datajson[0]['GPSPoint_lat'];
                    $lon = $datajson[0]['GPSPoint_lon'];
                    $address = 'Carretera Saltillo-Torreón,Coahuila, México';//$datajson[0]['Address'];

                    //Enviar datos a los correo del cliente
                    if (count($sql) !== 0) {
                        for($i = 0; $i<count($sql); $i++){
                            //separacion por cada correo obtenido
                            Mail::to($sql[$i]->correo)->send(new NotificaClientesGps($viaje, $operador, $unidad, $placas, $remolque, $cliente, $ruta, $origen, $destino, $status, $fecha, $lat, $lon, $address));
                            Log::info('Correo Gps enviado!');
                        }
                        //update tabla send_txt a 0 terminar el proceso
                        if (DB::connection('sqlsrvpro')->table("bitacora_clientes_gps")->where([ ['id_incremental', '=', $value->id_incremental] ])->update(['latitud' => $lat, 'longitud' => $lon, 'direccion' => $address, 'send_txt' => '0'])) {
                            Log::info('tabla bitacora_clientes_gps actualizada!');
                        } else {
                            Log::error('No se pudo Actualizar tabla: bitacora_clientes_gps');
                        }
                    } else {

                        $error = new clientes();
                        $error->errorCorreo($cliente, $idcliente);
        
                        //update tabla send_txt a 2 Falta de correo
                        if (DB::connection('sqlsrvpro')->table("bitacora_clientes_gps")->where([ ['id_incremental', '=', $value->id_incremental] ])->update(['send_txt' => '2'])) {
                            Log::info('Actualiza send_txt = 2 falta de correo');
                        }
                    }
                }
            }
        }
    }
    public function errorCorreo($cliente, $idcliente) {
        Log::alert('No existe correo cliente id: '.$idcliente);
        // informar a los correo de Trafico en general
        Mail::to(env('MAIL_TRAFICOS'))->send(new messagesend($cliente));
        Log::info('Correo enviado, falta de correo!');
    }
}
