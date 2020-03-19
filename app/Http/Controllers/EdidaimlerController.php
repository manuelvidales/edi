<?php

namespace App\Http\Controllers;

use App\edidaimler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\messagesend;

class EdidaimlerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

/** Separar por * cada Fila del array */

public function path()
{
    $name= 'RYD204ATIH.20200310134106599.1178211835.txt';
    $dir = 'app/public/'.$name;
    $path = file::get(storage_path($dir));
    //echo $path;
    //dd($path);
    $array = explode("~", $path);

echo '
<div class="container p-3">
<div class="row">
    <div class="col-sm-6 text-danger">
    <label> <strong>Archivo:// '.$name.'<br>Codigo: 204</strong></label>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-sm-6">
';
    //separacion por row requerido
    $row0 = $array[0];
    $tr0 = explode ("*", $row0);
        $row0td0= $tr0[0];
        $row0td5= $tr0[5];
        $row0td6= $tr0[6];
        $row0td7= $tr0[7];
        $row0td8= $tr0[8];
        $row0td11= $tr0[11];
        $row0td12= $tr0[12];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>0 - '.$row0td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>id_qualifier_sender</td><td>'.$row0td5.'</td></tr>
            <tr><td>id_sender</td><td>'.$row0td6.'</td></tr>
            <tr><td>id_qualifier_receiver</td><td>'.$row0td7.'</td></tr>
            <tr><td>id_receiver</td><td>'.$row0td8.'</td></tr>
            <tr><td>version_number</td><td>'.$row0td11.'</td></tr>
            <tr><td>control_number</td><td>'.$row0td12.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row1 = $array[1];
    $tr1 = explode ("*", $row1);
        $row1td0= $tr1[0];
        $row1td2= $tr1[2];
        $row1td7= $tr1[7];
        $row1td8= $tr1[8];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th> 1 - '.$row1td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>sender_code</td><td>'.$row1td2.'</td></tr>
            <tr><td>agency_code</td><td>'.$row1td7.'</td></tr>
            <tr><td>industry_identifier</td><td>'.$row1td8.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row3 = $array[3];
    $tr3 = explode ("*", $row3);
        $row3td0= $tr3[0];
        $row3td2= $tr3[2];
        $row3td4= $tr3[4];
        $row3td6= $tr3[6];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>3 - '.$row3td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">                
            <tr><td>alpha_code</td><td>'.$row3td2.'</td></tr>
            <tr><td>shipment_identification_number</td><td>'.$row3td4.'</td></tr>
            <tr><td>method_payment</td><td>'.$row3td6.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row7 = $array[7];
    $tr7 = explode ("*", $row7);
        $row7td0= $tr7[0];
        $row7td1= $tr7[1];
        $row7td2= $tr7[2];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>7 - '.$row7td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>reference_identification</td><td>'.$row7td1.'</td></tr>
            <tr><td>reference_identification_qualifier</td><td>'.$row7td2.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row10 = $array[10];
    $tr10 = explode ("*", $row10);
        $row10td0= $tr10[0];
        $row10td1= $tr10[1];
        $row10td2= $tr10[2];
        $row10td3= $tr10[3];
        $row10td4= $tr10[4];
        $row10td5= $tr10[5];
        $row10td6= $tr10[6];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>10 - '.$row10td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>stop_number_load</td><td>'.$row10td1.'</td></tr>
            <tr><td>stop_reason_code_load</td><td>'.$row10td2.'</td></tr>
            <tr><td>weight_load</td><td>'.$row10td3.'</td></tr>
            <tr><td>weight_units_load</td><td>'.$row10td4.'</td></tr>
            <tr><td>quantity_load</td><td>'.$row10td5.'</td></tr>
            <tr><td>unit_for_measurement_load</td><td>'.$row10td6.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row13 = $array[13];
    $tr13 = explode ("*", $row13);
        $row13td0= $tr13[0];
        $row13td2= $tr13[2];
        $row13td4= $tr13[4];
        $row13td5= $tr13[5];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>13 - '.$row13td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>load_date_1</td><td>'.$row13td2.'</td></tr>
            <tr><td>load_time_1</td><td>'.$row13td4.'</td></tr>
            <tr><td>load_time_code_1</td><td>'.$row13td5.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row14 = $array[14];
    $tr14 = explode ("*", $row14);
        $row14td0= $tr14[0];
        $row14td1= $tr14[1];
        $row14td2= $tr14[2];
        $row14td3= $tr14[3];
        $row14td4= $tr14[4];
        $row14td5= $tr14[5];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>14 - '.$row14td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>load_date_qualifier_2</td><td>'.$row14td1.'</td></tr>
            <tr><td>load_date_2</td><td>'.$row14td2.'</td></tr>
            <tr><td>load_time_qualifier_2</td><td>'.$row14td3.'</td></tr>
            <tr><td>load_time_2</td><td>'.$row14td4.'</td></tr>
            <tr><td>load_time_code_2</td><td>'.$row14td5.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row15 = $array[15];
    $tr15 = explode ("*", $row15);
        $row15td0= $tr15[0];
        $row15td2= $tr15[2];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>15 - '.$row15td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>origin</td><td>'.$row15td2.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row16 = $array[16];
    $tr16 = explode ("*", $row16);
        $row16td0= $tr16[0];
        $row16td1= $tr16[1];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>16 - '.$row16td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>addres_origin</td><td>'.$row16td1.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row17 = $array[17];
    $tr17 = explode ("*", $row17);
        $row17td0= $tr17[0];
        $row17td1= $tr17[1];
        $row17td2= $tr17[2];
        $row17td3= $tr17[3];
        $row17td4= $tr17[4];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>17 - '.$row17td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>city_origin</td><td>'.$row17td1.'</td></tr>
            <tr><td>state_origin</td><td>'.$row17td2.'</td></tr>
            <tr><td>postal_code_origin</td><td>'.$row17td3.'</td></tr>
            <tr><td>country_origin</td><td>'.$row17td4.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row19 = $array[19];
    $tr19 = explode ("*", $row19);
        $row19td0= $tr19[0];
        $row19td1= $tr19[1];
        $row19td2= $tr19[2];
        $row19td3= $tr19[3];
        $row19td4= $tr19[4];
        $row19td5= $tr19[5];
        $row19td6= $tr19[6];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>19 - '.$row19td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>stop_number_stop1</td><td>'.$row19td1.'</td></tr>
            <tr><td>stop_reason_code_stop1</td><td>'.$row19td2.'</td></tr>
            <tr><td>weight_stop1</td><td>'.$row19td3.'</td></tr>
            <tr><td>weight_units_stop1</td><td>'.$row19td4.'</td></tr>
            <tr><td>quantity_stop1</td><td>'.$row19td5.'</td></tr>
            <tr><td>unit_for_measurement_stop1</td><td>'.$row19td6.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row20 = $array[20];
    $tr20 = explode ("*", $row20);
        $row20td0= $tr20[0];
        $row20td1= $tr20[1];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>20 - '.$row20td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>tracking_number</td><td>'.$row20td1.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row22 = $array[22];
    $tr22 = explode ("*", $row22);
        $row22td0= $tr22[0];
        $row22td2= $tr22[2];
        $row22td4= $tr22[4];
        $row22td5= $tr22[5];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>22 - '.$row22td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>stop1_date</td><td>'.$row22td2.'</td></tr>
            <tr><td>stop1_time</td><td>'.$row22td4.'</td></tr>
            <tr><td>stop1_time_code</td><td>'.$row22td5.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row24 = $array[24];
    $tr24 = explode ("*", $row24);
        $row24td0= $tr24[0];
        $row24td2= $tr24[2];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>24 - '.$row24td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>stop1</td><td>'.$row24td2.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row25 = $array[25];
    $tr25 = explode ("*", $row25);
        $row25td0= $tr25[0];
        $row25td1= $tr25[1];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>25 - '.$row25td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>addres_stop1</td><td>'.$row25td1.'</td></tr>
            </tbody></table>';
            echo "<hr>";

    $row26 = $array[26];
    $tr26 = explode ("*", $row26);
        $row26td0= $tr26[0];
        $row26td1= $tr26[1];
        $row26td2= $tr26[2];
        $row26td3= $tr26[3];
        $row26td4= $tr26[4];
            echo '
            <table class="table table-sm table-striped text-center">
            <thead><tr><th>26 - '.$row26td0.'</th><th>Valores</th></tr></thead>
            <tbody class="text-center">
            <tr><td>city_stop1</td><td>'.$row26td1.'</td></tr>
            <tr><td>state_stop1</td><td>'.$row26td2.'</td></tr>
            <tr><td>postal_code_stop1</td><td>'.$row26td3.'</td></tr>
            <tr><td>country_stop1</td><td>'.$row26td4.'</td></tr>
            </tbody></table>';
            echo "<hr>";

   

    echo '</div><div class="col-sm-5"></div></div>';

    return \view('vista');

}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name= 'RYD204ATIH.20200310134106599.1178211835.txt';
        $dir = 'app/public/'.$name;
        $path = file::get(storage_path($dir));
        $array = explode("~", $path);

        $row0 = $array[0];
        $tr0 = explode ("*", $row0);
            $row0td5= $tr0[5];
            $row0td6= $tr0[6];
            $row0td7= $tr0[7];
            $row0td8= $tr0[8];
            $row0td11= $tr0[11];
            $row0td12= $tr0[12];

        $row1 = $array[1];
        $tr1 = explode ("*", $row1);
            $row1td2= $tr1[2];
            $row1td7= $tr1[7];
            $row1td8= $tr1[8];

        $row3 = $array[3];
        $tr3 = explode ("*", $row3);
            $row3td2= $tr3[2];
            $row3td4= $tr3[4];
            $row3td6= $tr3[6];

        $row7 = $array[7];
        $tr7 = explode ("*", $row7);
            $row7td1= $tr7[1];
            $row7td2= $tr7[2];

        $row10 = $array[10];
        $tr10 = explode ("*", $row10);
            $row10td1= $tr10[1];
            $row10td2= $tr10[2];
            $row10td3= $tr10[3];
            $row10td4= $tr10[4];
            $row10td5= $tr10[5];
            $row10td6= $tr10[6];

        $row13 = $array[13];
        $tr13 = explode ("*", $row13);
            $row13td2= $tr13[2];
            $row13td4= $tr13[4];
            $row13td5= $tr13[5];

        $row14 = $array[14];
        $tr14 = explode ("*", $row14);
            $row14td1= $tr14[1];
            $row14td2= $tr14[2];
            $row14td3= $tr14[3];
            $row14td4= $tr14[4];
            $row14td5= $tr14[5];

        $row15 = $array[15];
        $tr15 = explode ("*", $row15);
            $row15td2= $tr15[2];

        $row16 = $array[16];
        $tr16 = explode ("*", $row16);
            $row16td1= $tr16[1];

        $row17 = $array[17];
        $tr17 = explode ("*", $row17);
            $row17td1= $tr17[1];
            $row17td2= $tr17[2];
            $row17td3= $tr17[3];
            $row17td4= $tr17[4];

        $row19 = $array[19];
        $tr19 = explode ("*", $row19);
            $row19td1= $tr19[1];
            $row19td2= $tr19[2];
            $row19td3= $tr19[3];
            $row19td4= $tr19[4];
            $row19td5= $tr19[5];
            $row19td6= $tr19[6];

        $row20 = $array[20];
        $tr20 = explode ("*", $row20);
            $row20td1= $tr20[1];

        $row22 = $array[22];
        $tr22 = explode ("*", $row22);
            $row22td2= $tr22[2];
            $row22td4= $tr22[4];
            $row22td5= $tr22[5];

        $row24 = $array[24];
        $tr24 = explode ("*", $row24);
            $row24td2= $tr24[2];

        $row25 = $array[25];
        $tr25 = explode ("*", $row25);
            $row25td1= $tr25[1];

        $row26 = $array[26];
        $tr26 = explode ("*", $row26);
            $row26td1= $tr26[1];
            $row26td2= $tr26[2];
            $row26td3= $tr26[3];
            $row26td4= $tr26[4];

        $save = \DB::connection('sqlsrv')->table("edi_daimler")->insert([
            'id_qualifier_sender' => $row0td5,
            'id_sender' => $row0td6,
            'id_qualifier_receiver' => $row0td7,
            'id_receiver' => $row0td8,
            'version_number' => $row0td11,
            'control_number' => $row0td12,
            'sender_code' => $row1td2,
            'agency_code' => $row1td7,
            'industry_identifier' => $row1td8,
            'alpha_code' => $row3td2,
            'shipment_identification_number' => $row3td4,
            'method_payment' => $row3td6,
            'reference_identification' => $row7td1,
            'reference_identification_qualifier' => $row7td2,
            'stop_number_load' => $row10td1,
            'stop_reason_code_load' => $row10td2,
            'weight_load' => $row10td3,
            'weight_units_load' => $row10td4,
            'quantity_load' => $row10td5,
            'unit_for_measurement_load' => $row10td6,
            'load_date_1' => $row13td2,
            'load_time_1' => $row13td4,
            'load_time_code_1' => $row13td5,
            'load_date_qualifier_2' => $row14td1,
            'load_date_2' => $row14td2,
            'load_time_qualifier_2' => $row14td3,
            'load_time_2' => $row14td4,
            'load_time_code_2' => $row14td5,
            'origin' => $row15td2,
            'addres_origin' => $row16td1,
            'city_origin' => $row17td1,
            'state_origin' => $row17td2,
            'postal_code_origin' => $row17td3,
            'country_origin' => $row17td4,
            'stop_number_stop1' => $row19td1,
            'stop_reason_code_stop1' => $row19td2,
            'weight_stop1' => $row19td3,
            'weight_units_stop1' => $row19td4,
            'quantity_stop1' => $row19td5,
            'unit_for_measurement_stop1' => $row19td6,
            'tracking_number' => $row20td1,
            'stop1_date' => $row22td2,
            'stop1_time' => $row22td4,
            'stop1_time_code' => $row22td5,
            'stop1' => $row24td2,
            'addres_stop1' => $row25td1,
            'city_stop1' => $row26td1,
            'state_stop1' => $row26td2,
            'postal_code_stop1' => $row26td3,
            'country_stop1' => $row26td4,
        ]);
    
        return back()->with('msg', 'Insertados con exito');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\edidaimler  $edidaimler
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $valida = DB::table('edidaimlers')->where('shipment_id', $id)->first();

        if (empty($valida)) {
            return \view('daimler.alert'); //no continuar
        } else {
            
            if (empty($valida->response)) { //valor null
                
                $data = \DB::connection('sqlsrv')->table("edi_daimler")->select('shipment_identification_number', 'origin','addres_origin','city_origin','state_origin','country_origin','load_date_1','load_time_1','load_time_code_1', 'stop1','addres_stop1','city_stop1','state_stop1','country_stop1')->where('shipment_identification_number', '=', $id)->first();

                return \view('daimler.confirm', compact('data'));

            } else {
                return \view('daimler.alert'); //no continuar
            }
            
        }
    
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\edidaimler  $edidaimler
     * @return \Illuminate\Http\Response
     */
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
            $datos = \DB::connection('sqlsrv')->table("edi_daimler")->select('shipment_identification_number','Alpha_code','reference_identification_qualifier','reference_identification','load_date_qualifier_2','load_date_2','load_time_qualifier_2','load_time_2','load_time_code_2','id_qualifier_sender','id_sender','id_qualifier_receiver',
            'id_receiver','version_number','control_number','sender_code','agency_code',
            'industry_identifier',)->where('shipment_identification_number', '=', $Request->orderid)->first();

        //Almacenamos la respuesta en tabla 990
            DB::connection('sqlsrv')->table("edi_daimler_990")->insert([  
                'shipment_identification_number' => $datos->shipment_identification_number,
                'Alpha_code' => $datos->Alpha_code,
                'reservation_action_code' => $Request->response,
                'reference_identification_qualifier' => $datos->reference_identification_qualifier,
                'reference_identification' => $datos->reference_identification,
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
            ]);

        //consultamos la tabla 990 para confirmar el id almacenado

        $data990 = \DB::connection('sqlsrv')->table("edi_daimler_990")->where('shipment_identification_number', '=', $Request->orderid)->first();

        //Crear archivo TxT 990
        $id = $data990->id_incremental;
        $i = strlen($id);
        
        if ($i == 1) {
            $idnew = '00000000'.$id;
        } elseif ($i == 2) {
            $idnew = '0000000'.$id;
        } elseif ($i == 3) {
            $idnew = '000000'.$id;
        }
        elseif ($i == 4) {
            $idnew = '00000'.$id;
        }
        elseif ($i == 5) {
            $idnew = '0000'.$id;
        }
        elseif ($i == 6) {
            $idnew = '000'.$id;
        }
        elseif ($i == 7) {
            $idnew = '00'.$id;
        }
        elseif ($i == 8) {
            $idnew = '0'.$id;
        }
        elseif ($i == 9) {
            $idnew = $id;
        }
        else{
            $idnew = 'null';
        }
      
        $tr0td5= $data990->id_qualifier_receiver;
        $tr0td6= $data990->id_receiver;
        $tr0td7= $data990->id_qualifier_sender;
        $tr0td8= trim($data990->id_sender);//retira espacio en blanco
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
        Storage::disk('sftp')->put('files990/'.$filename.'.txt', "ISA*00*          *00*          *".$tr0td5."*".$tr0td6."*".$tr0td7."*".$tr0td8."      *".$tr0td9."*".$tr0td10."*".$tr0td11."*".$tr0td12."*".$idnew."*0*T*^~GS*GF*".$tr1td2."*".$tr1td3."*".$tr1td4."*".$tr1td5."*".$id."*".$tr1td7."*".$tr1td8."~ST*990*0001~B1*".$tr3td1."*".$tr3td2."**".$tr3td4."~N9*".$tr4td1."*".$tr4td2."~G62*".$tr5td1."*".$tr5td2."*".$tr5td3."*".$tr5td4."*".$tr5td5."~SE*5*0001~GE*1*".$id."~IEA*1*".$idnew."~");

        } else {

            return response()->json(['message'=>'Respuesta incorrecta'], 200);

        }
        
        return response()->json(['message'=>'successful response'], 200);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\edidaimler  $edidaimler
     * @return \Illuminate\Http\Response
     */
    public function destroy(edidaimler $edidaimler)
    {
        //
    }
}
