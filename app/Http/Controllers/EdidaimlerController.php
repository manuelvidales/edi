<?php

namespace App\Http\Controllers;

use App\edidaimler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EdidaimlerController extends Controller
{
    public function index()
    {
        $ftp_server = env('FTP_HOST');
        $ftp_user = env('FTP_USERNAME');
        $ftp_pass = env('FTP_PASSWORD');
        // establecer una conexión o finalizarla
        $conn_id = ftp_connect($ftp_server) or die("No se pudo conectar a $ftp_server");
        $login = ftp_login($conn_id, $ftp_user, $ftp_pass);
        //validar conexion FTP        

        if ( @$login ) {
        // Obtener los archivos del directorio ftp
            $filesnew = ftp_nlist($conn_id, 'fromRyder');
            $filessend = ftp_nlist($conn_id, 'toRyder_arch');
        }else{
            Log::warning('Error en conexion ftp');
            $filesnew = '0';
            $filessend = '0';
        }
        
        $ships = DB::table('edidaimlers')->latest()->get();
        
        ftp_close($conn_id); // cerrar la conexión ftp

        return \view('daimler.index', compact('ships','filesnew','filessend'));
    }

    public function filesdown($file)
    {
        return Storage::disk('public')->download($file);
    }
    
    public function show($id)
    {
        $valida = edidaimler::where('shipment_id', $id)->where('purpose_code', '00')->Orwhere('purpose_code', '05')->first();
        if (empty($valida)) {
            return \view('daimler.alert'); //no continuar
        } else {
            if (empty($valida->response)) { //valor null
                $sql = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where('shipment_identification_number', '=', $id)->first();

            //dd($sql);

                    if($sql->stop8 ==! null){ //no es null
                        $last = $sql->stop8;
                        $addres_last = $sql->stop8_addres;
                        $city_last = $sql->stop8_city;
                        $state_last = $sql->stop8_state;
                        $country_last = $sql->stop8_country;
                    }elseif($sql->stop7 ==! null){
                        $last = $sql->stop7;
                        $addres_last = $sql->stop7_addres;
                        $city_last = $sql->stop7_city;
                        $state_last = $sql->stop7_state;
                        $country_last = $sql->stop7_country;
                    }elseif($sql->stop6 ==! null){
                        $last = $sql->stop6;
                        $addres_last = $sql->stop6_addres;
                        $city_last = $sql->stop6_city;
                        $state_last = $sql->stop6_state;
                        $country_last = $sql->stop6_country;
                    }elseif($sql->stop5 ==! null){
                        $last = $sql->stop5;
                        $addres_last = $sql->stop5_addres;
                        $city_last = $sql->stop5_city;
                        $state_last = $sql->stop5_state;
                        $country_last = $sql->stop5_country;
                    }elseif($sql->stop4 ==! null){
                        $last = $sql->stop4;
                        $addres_last = $sql->stop4_addres;
                        $city_last = $sql->stop4_city;
                        $state_last = $sql->stop4_state;
                        $country_last = $sql->stop4_country;
                    }elseif($sql->stop3 ==! null){
                        $last = $sql->stop3;
                        $addres_last = $sql->stop3_addres;
                        $city_last = $sql->stop3_city;
                        $state_last = $sql->stop3_state;
                        $country_last = $sql->stop3_country;
                    }elseif($sql->stop2 ==! null){
                        $last = $sql->stop2;
                        $addres_last = $sql->stop2_addres;
                        $city_last = $sql->stop2_city;
                        $state_last = $sql->stop2_state;
                        $country_last = $sql->stop2_country;
                    }
                    $datos = array(
                        'shipment_identification_number' => $sql->shipment_identification_number,
                        'origin' => $sql->stop1,
                        'addres_origin' => $sql->stop1_addres,
                        'city_origin' => $sql->stop1_city,
                        'state_origin' => $sql->stop1_state,
                        'country_origin' => $sql->stop1_country,
                        'load_date' => $sql->stop1_date,
                        'load_time' => $sql->stop1_time,
                        'last_destin' => $last,
                        'addres_last' => $addres_last,
                        'city_last' => $city_last,
                        'state_last' => $state_last,
                        'country_last' => $country_last);               
                    $data = array_values($datos);
                    return \view('daimler.confirm', compact('data' ,'sql'));
            } else {
                return \view('daimler.alert'); //no continuar
            }
        }
    }

    public function respuesta(Request $Request)
    {
        $today = date_create('now');
        if (empty($Request->all())) {
            return \view('daimler.alert'); //no continuar
        } else {
            if ($Request->response == 'A' || $Request->response == 'D') {
            //almacena la respuesta en DB local del archivo recibido
                $mysql = DB::table('edidaimlers')->where('shipment_id', $Request->orderid)->update(['response' => $Request->response, 'updated_at' => $today->format('Y-m-d H:i:s') ]);
                    if(empty($mysql)){
                        Log::warning('Error en almacenar (mysql) respuesta de orden: '.$Request->orderid);
                    }
            //consultar datos por id de la orden se envio
                $datos = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where('shipment_identification_number', '=', $Request->orderid)->first();
            //Almacenamos la respuesta en tabla 990
                $update990 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_990")->insert([  
                    'shipment_identification_number' => $datos->shipment_identification_number,
                    'alpha_code' => $datos->alpha_code,
                    'reservation_action_code' => $Request->response,
                    'reference_identification_qualifier' => $datos->reference_identification_qualifier,
                    'reference_identification' => $datos->reference_identification,
                    // no se guardaron
                        //'weight_units_load' => $datos->weight_units_load,
                        //'weight_load' => $datos->weight_load,
                        //'quantity_load' => $datos->quantity_load,
                    //esta en G62 solo guardamos fecha, hora y code
                        // 'load_date_qualifier_2' => $datos->load_date_qualifier_2,
                        // 'load_date_2' => $datos->load_date_2,
                        // 'load_time_qualifier_2' => $datos->load_time_qualifier_2,
                        // 'load_time_2' => $datos->load_time_2,
                        // 'load_time_code_2' => $datos->load_time_code_2,
                    'id_qualifier_sender' => $datos->id_qualifier_sender,
                    'id_sender' => $datos->id_sender,
                    'id_qualifier_receiver' => $datos->id_qualifier_receiver,
                    'id_receiver' => $datos->id_receiver,
                    'version_number' => $datos->version_number,
                    'control_number' => $datos->control_number,
                    'sender_code' => $datos->sender_code,
                    'agency_code' => $datos->agency_code,
                    'industry_identifier' => $datos->industry_identifier,
                    'date_time' => $today->format('Ymd H:i:s.000'),
                // no se guardaron 
                    // 'volume_unit_qualifier_load' => '',
                    // 'volume_load' => '',
                    'tracking_number_origin' => $datos->stop1_tracking_number,
                    // esta en L11[2] solo se guarda el L11[1]
                    //'id_tracking_number' => $datos->id_tracking_number,
                    'purpose_code' => $datos->purpose_code,
                    'tracking_number_stop1' => $datos->stop2_tracking_number //varia los stop debera ser el ultimo se deja el stop2
                ]);
                if (empty($update990)) { Log::critical('Fallo al actualizar tabla: edi_daimler_990'); }
                else {
                    Log::info('tabla: edi_daimler_990 actualizada con exito');
                }
            } else {
                return response()->json(['message'=>'Respuesta incorrecta'], 200);
            }
            return response()->json(['message'=>'successful response'], 200);
        }
    }
}
