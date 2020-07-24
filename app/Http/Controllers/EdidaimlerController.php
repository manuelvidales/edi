<?php

namespace App\Http\Controllers;

use App\edidaimler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EdidaimlerController extends Controller
{
    public function index()
    {
        $ships = edidaimler::all();
        return \view('daimler.index', compact('ships'));
    }
    
    public function show($id)
    {
        $valida = DB::table('edidaimlers')->where('shipment_id', $id)->first();
        if (empty($valida)) {
            return \view('daimler.alert'); //no continuar
        } else {
            if (empty($valida->response)) { //valor null
                
                $data = DB::connection(env('DB_DAIMLER'))->table("edi_daimler")->select('shipment_identification_number', 'origin','addres_origin','city_origin','state_origin','country_origin','load_date_1','load_time_1','load_time_code_1', 'stop1','addres_stop1','city_stop1','state_stop1','country_stop1')->where('shipment_identification_number', '=', $id)->first();

                return \view('daimler.confirm', compact('data'));

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
            DB::table('edidaimlers')->where('shipment_id', $Request->orderid)->update(['response' => $Request->response, 'updated_at' => $today->format('Y-m-d H:i:s') ]);
        //consultar datos por id de la orden se envio
            $datos = DB::connection(env('DB_DAIMLER'))->table("edi_daimler")->select('shipment_identification_number','purpose_code','Alpha_code','reference_identification_qualifier','reference_identification','load_date_qualifier_2','load_date_2','load_time_qualifier_2','load_time_2','load_time_code_2','id_qualifier_sender','id_sender','id_qualifier_receiver',
            'id_receiver','version_number','control_number','sender_code','agency_code',
            'industry_identifier','weight_units_load','weight_load','quantity_load','tracking_number_origin','id_tracking_number','tracking_number_stop1')->where('shipment_identification_number', '=', $Request->orderid)->first();
        //Almacenamos la respuesta en tabla 990
            DB::connection(env('DB_DAIMLER'))->table("edi_daimler_990")->insert([  
                'shipment_identification_number' => $datos->shipment_identification_number,
                'Alpha_code' => $datos->Alpha_code,
                'reservation_action_code' => $Request->response,
                'reference_identification_qualifier' => $datos->reference_identification_qualifier,
                'reference_identification' => $datos->reference_identification,
                'weight_units_load' => $datos->weight_units_load,
                'weight_load' => $datos->weight_load,
                'quantity_load' => $datos->quantity_load,
                'load_date_qualifier_2' => $datos->load_date_qualifier_2,
                'load_date_2' => $datos->load_date_2,
                'load_time_qualifier_2' => $datos->load_time_qualifier_2,
                'load_time_2' => $datos->load_time_2,
                'load_time_code_2' => $datos->load_time_code_2,
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
                'volume_unit_qualifier_load' => '',
                'volume_load' => '',
                'tracking_number_origin' => $datos->tracking_number_origin,
                'id_tracking_number' => $datos->id_tracking_number,
                'purpose_code' => $datos->purpose_code,
                'tracking_number_stop1' => $datos->tracking_number_stop1
            ]);
        //consultamos la tabla 990 para confirmar el id y datos almacenado
        $data990 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_990")->where('shipment_identification_number', '=', $Request->orderid)->first();
        //Crear archivo TxT 990
        $id = $data990->id_incremental;
        $i = strlen($id);
        if     ($i == 1) { $idnew = '00000000'.$id; }
        elseif ($i == 2) { $idnew = '0000000'.$id; }
        elseif ($i == 3) { $idnew = '000000'.$id; }
        elseif ($i == 4) { $idnew = '00000'.$id; }
        elseif ($i == 5) { $idnew = '0000'.$id; }
        elseif ($i == 6) { $idnew = '000'.$id; } 
        elseif ($i == 7) { $idnew = '00'.$id; }
        elseif ($i == 8) { $idnew = '0'.$id; }
        elseif ($i == 9) { $idnew = $id; } 
        else { $idnew = 'null'; }
        $tr0td5= $data990->id_qualifier_receiver;
        $tr0td6= $data990->id_receiver;
        $tr0td7= $data990->id_qualifier_sender;
        $tr0td8= $data990->id_sender;
        $tr0td9= date('ymd', strtotime($data990->date_time));
        $tr0td10= date('Hi', strtotime($data990->date_time));
        $tr0td11= $data990->version_number;
        $tr0td12= $data990->control_number;    
        $tr1td2= trim($data990->id_receiver);//retira espacio en blanco
        $tr1td3= $data990->sender_code;
        $tr1td4= date('Ymd', strtotime($data990->date_time));
        $tr1td5= date('Hi', strtotime($data990->date_time));
        $id= $data990->id_incremental;
        $tr1td7= $data990->agency_code;
        $tr1td8= $data990->industry_identifier;    
        $tr3td1= $data990->alpha_code;
        $tr3td2= $data990->shipment_identification_number;
        $tr3td4= $data990->reservation_action_code;    
        $tr4td1= $data990->reference_identification_qualifier;
        $tr4td2= $data990->reference_identification;    
        $tr5td1= $data990->load_date_qualifier_2;
        $tr5td2= $data990->load_date_2;
        $tr5td3= $data990->load_time_qualifier_2;
        $tr5td4= $data990->load_time_2;
        $tr5td5= $data990->load_time_code_2;
        $filename = $data990->alpha_code.'_'.$data990->sender_code.'_990_'.$data990->load_date_2.'_'.$idnew;
        //Crear archivo TxT 990
        Storage::disk('ftp')->put('toRyder/'.$filename.'.txt', "ISA*00*          *00*          *".$tr0td5."*".$tr0td6."*".$tr0td7."*".$tr0td8."*".$tr0td9."*".$tr0td10."*".$tr0td11."*".$tr0td12."*".$idnew."*0*T*^~GS*GF*".$tr1td2."*".$tr1td3."*".$tr1td4."*".$tr1td5."*".$id."*".$tr1td7."*".$tr1td8."~ST*990*0001~B1*".$tr3td1."*".$tr3td2."**".$tr3td4."~N9*".$tr4td1."*".$tr4td2."~G62*".$tr5td1."*".$tr5td2."*".$tr5td3."*".$tr5td4."*".$tr5td5."~SE*5*0001~GE*1*".$id."~IEA*1*".$idnew."~");
        } else {
            return response()->json(['message'=>'Respuesta incorrecta'], 200);
        }
            return response()->json(['message'=>'successful response'], 200);
        }
    }
}
