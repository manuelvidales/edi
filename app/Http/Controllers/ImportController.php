<?php

namespace App\Http\Controllers;

use App\import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\messagesend;
use Illuminate\Support\Facades\Log;
use App\edidaimler;
use App\Mail\NotificaDaimler;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()

    {
        $files = edidaimler::where('status', null)->get();

        foreach ($files as $row) {
            $nameactual = substr($row->filename, 0, 10);
            
            if ($nameactual == 'fromRyder/'){
                $namenew = substr($row->filename, 10);
                //$id = $row->id;
                $update = EdiDaimler::findOrFail($row->id);
                $update->filename = $namenew;
                $update->save();
            }else {
                dd('no hubo conicidencias');
            }
        }

        dd('Cambio de nombres finalizado');
        


/*



        // $gps = DB::connection('sqlsrvpro')->table("edi_daimler_214_gps")->where('id_incremental', '=', '1070')->get();
        // dd($gps->all());

        $path_process = 'Daimler/fromRyder/';
        //$filename = 'RYD204ATIH.20200819165727467.835181109.txt';
        $filename = 'RYD204ATIH.20200819165728046.835181129.txt';
        //$filename = 'RYD204ATIH.20200813115031864.826430788.txt'; // sin error
        

        $read_file = Storage::disk('local')->get($path_process.$filename);//lectura local del archivo 204
        $array = explode("S5",$read_file); // sepracion por cada S5 que contiene
        //Log::info('Archivo separado en array S5');
        $count = count($array);
        for($i = 0; $i<$count; $i++){
            switch(substr($array[$i],0,3))
            {
            case "ISA":
                $header = explode("~",$array[$i]);// Encabezado
                //Campos selecionados
                $ISA = explode("*",$header[0]);
                $GS = explode("*",$header[1]);
                $ST = explode("*",$header[2]);
                $B2 = explode("*",$header[3]);
                $B2A = explode("*",$header[4]);
                $L11 = explode("*",$header[7]);
                //ISA
                    $id_qualifier_sender=$ISA[5];
                    $id_sender=$ISA[6];
                    $id_qualifier_receiver=$ISA[7];
                    $id_receiver=$ISA[8];
                    $version_number=$ISA[11];
                    $control_number=$ISA[12];
                //GS
                    $sender_code=$GS[2];
                    $agency_code=$GS[7];
                    $industry_identifier=$GS[8];
                //ST
                    $control_number_sender=$ST[2];
                //B2
                    $alpha_code=$B2[2];
                    $shipment_identification_number=$B2[4];
                    $method_payment=$B2[6];
                //B2A
                    $purpose_code = $B2A[1];
                //L11
                    $reference_identification=$L11[1];
                    $reference_identification_qualifier=$L11[2];
            break;                   
            case "*1*":
                $S5_uno = explode("~",$array[$i]); // S5 inicial
                $contS5uno = count($S5_uno);
                for($s=0; $s<$contS5uno; $s++){
                    $data1 = substr($S5_uno[$s],0,3);                    
                    if ($data1 == '*1*') {
                        $S5_1 = explode("*",$S5_uno[$s]);
                    }elseif ($data1 == 'L11') { //El primero de dos
                        $L11_1 = explode("*",$S5_uno[$s]);
                    }elseif ($data1 == 'G62') { //El primero de dos
                        $G62_1 = explode("*",$S5_uno[$s]);
                    }elseif ($data1 == 'N1*') {
                        $N1_1 = explode("*",$S5_uno[$s]);
                    }elseif ($data1 == 'N3*') {
                        $N3_1 = explode("*",$S5_uno[$s]);
                    }elseif ($data1 == 'N4*') {
                        $N4_1 = explode("*",$S5_uno[$s]);
                    }
                }
                //S5
                    if (empty($S5_1)){
                        $stop1_reason_code='null';
                    }else{
                        $stop1_reason_code=$S5_1[2];
                    }
                //L11
                    if (empty($L11_1)){
                        $stop1_tracking_number='null';
                    }else{
                        $stop1_tracking_number=$L11_1[1];
                    }
                //G62
                    if (empty($G62_1)){
                        $stop1_date='null';
                        $stop1_time='null';
                        $stop1_time_code='null';
                    }else{
                        $stop1_date=$G62_1[2];
                        $stop1_time=$G62_1[4];
                        $stop1_time_code=$G62_1[5];
                    }
                //N1
                    if (empty($N1_1)){
                        $stop1_identifier_code='null';
                        $stop1='null';
                    }else{
                        $stop1_identifier_code=$N1_1[1];
                        $stop1=$N1_1[2];
                    }
                //N3
                    if (empty($N3_1)){
                        $stop1_addres='null';
                    }else{
                        $stop1_addres=$N3_1[1];
                    }
                //N4
                    if (empty($N4_1)){
                        $stop1_city='null';
                        $stop1_state='null';
                        $stop1_postal_code='null';
                        $stop1_country='null';
                    }else{
                        $stop1_city=$N4_1[1];
                        $stop1_state=$N4_1[2];
                        $stop1_postal_code=$N4_1[3];
                        $stop1_country=$N4_1[4];
                    }
            break;
            case "*2*":
                $S5_dos = explode("~",$array[$i]);
                $contS5dos = count($S5_dos);
                for($s=0; $s<$contS5dos; $s++){
                    $data2 = substr($S5_dos[$s],0,3);
                    if ($data2 == '*2*') {
                        $S5_2 = explode("*",$S5_dos[$s]);
                    }elseif ($data2 == 'L11') { //El primero de dos
                        $L11_2 = explode("*",$S5_dos[$s]);
                    }elseif ($data2 == 'G62') { //El primero de dos
                        $G62_2 = explode("*",$S5_dos[$s]);
                    }elseif ($data2 == 'N1*') {
                        $N1_2 = explode("*",$S5_dos[$s]);
                    }elseif ($data2 == 'N3*') {
                        $N3_2 = explode("*",$S5_dos[$s]);
                    }elseif ($data2 == 'N4*') {
                        $N4_2 = explode("*",$S5_dos[$s]);
                    }
                }
                //S5
                    if (empty($S5_2)){
                        $stop2_reason_code='null';
                    }else{
                        $stop2_reason_code=$S5_2[2];
                    }
                //L11
                    if (empty($L11_2)){
                        $stop2_tracking_number='null';
                    }else{
                        $stop2_tracking_number=$L11_2[1];
                    }
                //G62
                    if (empty($G62_2)){
                        $stop2_date='null';
                        $stop2_time='null';
                        $stop2_time_code='null';
                    }else{
                        $stop2_date=$G62_2[2];
                        $stop2_time=$G62_2[4];
                        $stop2_time_code=$G62_2[5];
                    }
                //N1
                    if (empty($N1_2)){
                        $stop2_identifier_code='null';
                        $stop2='null';
                    }else{
                        $stop2_identifier_code=$N1_2[1];
                        $stop2=$N1_2[2];
                    }
                //N3
                    if (empty($N3_2)){
                        $stop2_addres='null';
                    }else{
                        $stop2_addres=$N3_2[1];
                    }
                //N4
                    if (empty($N4_2)){
                        $stop2_city='null';
                        $stop2_state='null';
                        $stop2_postal_code='null';
                        $stop2_country='null';
                    }else{
                        $stop2_city=$N4_2[1];
                        $stop2_state=$N4_2[2];
                        $stop2_postal_code=$N4_2[3];
                        $stop2_country=$N4_2[4];
                    }          
            break;
            case "*3*":
                $S5_tres = explode("~",$array[$i]);
                $contS5tres = count($S5_tres);
                for($s=0; $s<$contS5tres; $s++){
                    $data3 = substr($S5_tres[$s],0,3);                    
                    if ($data3 == '*3*') {
                        $S5_3 = explode("*",$S5_tres[$s]);
                    }elseif ($data3 == 'L11') { //El primero de dos
                        $L11_3 = explode("*",$S5_tres[$s]);
                    }elseif ($data3 == 'G62') { //El primero de dos
                        $G62_3 = explode("*",$S5_tres[$s]);
                    }elseif ($data3 == 'N1*') {
                        $N1_3 = explode("*",$S5_tres[$s]);
                    }elseif ($data3 == 'N3*') {
                        $N3_3 = explode("*",$S5_tres[$s]);
                    }elseif ($data3 == 'N4*') {
                        $N4_3 = explode("*",$S5_tres[$s]);
                    }
                }
                //S5
                    if (empty($S5_3)){
                        $stop3_reason_code='null';
                    }else{
                        $stop3_reason_code=$S5_3[2];
                    }
                //L11
                    if (empty($L11_3)){
                        $stop3_tracking_number='null';
                    }else{
                        $stop3_tracking_number=$L11_3[1];
                    }
                //G62
                    if (empty($G62_3)){
                        $stop3_date='null';
                        $stop3_time='null';
                        $stop3_time_code='null';
                    }else{
                        $stop3_date=$G62_3[2];
                        $stop3_time=$G62_3[4];
                        $stop3_time_code=$G62_3[5];
                    }
                //N1
                    if (empty($N1_3)){
                        $stop3_identifier_code='null';
                        $stop3='null';
                    }else{
                        $stop3_identifier_code=$N1_3[1];
                        $stop3=$N1_3[2];
                    }
                //N3
                    if (empty($N3_3)){
                        $stop3_addres='null';
                    }else{
                        $stop3_addres=$N3_3[1];
                    }
                //N4
                    if (empty($N4_3)){
                        $stop3_city='null';
                        $stop3_state='null';
                        $stop3_postal_code='null';
                        $stop3_country='null';
                    }else{
                        $stop3_city=$N4_3[1];
                        $stop3_state=$N4_3[2];
                        $stop3_postal_code=$N4_3[3];
                        $stop3_country=$N4_3[4];
                    }
            break;

            case "*4*":
                $S5_cuatro = explode("~",$array[$i]);
                //Campos selecionados
                $S5_4 = explode("*",$S5_cuatro[0]);
                $L11_4 = explode("*",$S5_cuatro[1]);
                $G62_4 = explode("*",$S5_cuatro[3]);
                $N1_4 = explode("*",$S5_cuatro[5]);
                $N3_4 = explode("*",$S5_cuatro[6]);
                $N4_4 = explode("*",$S5_cuatro[7]);
                //S5
                    $stop4_reason_code=$S5_4[2];
                //L11
                    $stop4_tracking_number=$L11_4[1];
                //G62
                    $stop4_date=$G62_4[2];
                    $stop4_time=$G62_4[4];
                    $stop4_time_code=$G62_4[5];
                //N1
                    $stop4_identifier_code=$N1_4[1];
                    $stop4=$N1_4[2];
                //N3
                    $stop4_addres=$N3_4[1];
                //N4
                    $stop4_city=$N4_4[1];
                    $stop4_state=$N4_4[2];
                    $stop4_postal_code=$N4_4[3];
                    $stop4_country=$N4_4[4];
            break;
            case "*5*":
                $S5_cinco = explode("~",$array[$i]);
                //Campos selecionados
                $S5_5 = explode("*",$S5_cinco[0]);
                $L11_5 = explode("*",$S5_cinco[1]);
                $G62_5 = explode("*",$S5_cinco[3]);
                $N1_5 = explode("*",$S5_cinco[5]);
                $N3_5 = explode("*",$S5_cinco[6]);
                $N4_5 = explode("*",$S5_cinco[7]);

                //S5
                    $stop5_reason_code=$S5_5[2];
                //L11
                    $stop5_tracking_number=$L11_5[1];
                //G62
                    $stop5_date=$G62_5[2];
                    $stop5_time=$G62_5[4];
                    $stop5_time_code=$G62_5[5];
                //N1
                    $stop5_identifier_code=$N1_5[1];
                    $stop5=$N1_5[2];
                //N3
                    $stop5_addres=$N3_5[1];
                //N4
                    $stop5_city=$N4_5[1];
                    $stop5_state=$N4_5[2];
                    $stop5_postal_code=$N4_5[3];
                    $stop5_country=$N4_5[4];
            break;
            case "*6*":
                $S5_seis = explode("~",$array[$i]);
                //Campos selecionados
                $S5_6 = explode("*",$S5_seis[0]);
                $L11_6 = explode("*",$S5_seis[1]);
                $G62_6 = explode("*",$S5_seis[3]);
                $N1_6 = explode("*",$S5_seis[5]);
                $N3_6 = explode("*",$S5_seis[6]);
                $N4_6 = explode("*",$S5_seis[7]);
                //S5
                    $stop6_reason_code=$S5_6[2];
                //L11
                    $stop6_tracking_number=$L11_6[1];
                //G62
                    $stop6_date=$G62_6[2];
                    $stop6_time=$G62_6[4];
                    $stop6_time_code=$G62_6[5];
                //N1
                    $stop6_identifier_code=$N1_6[1];
                    $stop6=$N1_6[2];
                //N3
                    $stop6_addres=$N3_6[1];
                //N4
                    $stop6_city=$N4_6[1];
                    $stop6_state=$N4_6[2];
                    $stop6_postal_code=$N4_6[3];
                    $stop6_country=$N4_6[4];
            break;
            case "*7*":
                $S5_siete = explode("~",$array[$i]);
                //Campos selecionados
                $S5_7 = explode("*",$S5_siete[0]);
                $L11_7 = explode("*",$S5_siete[1]);
                $G62_7 = explode("*",$S5_siete[3]);
                $N1_7 = explode("*",$S5_siete[5]);
                $N3_7 = explode("*",$S5_siete[6]);
                $N4_7 = explode("*",$S5_siete[7]);
                //S5
                    $stop7_reason_code=$S5_7[2];
                //L11
                    $stop7_tracking_number=$L11_7[1];
                //G62
                    $stop7_date=$G62_7[2];
                    $stop7_time=$G62_7[4];
                    $stop7_time_code=$G62_7[5];
                //N1
                    $stop7_identifier_code=$N1_7[1];
                    $stop7=$N1_7[2];
                //N3
                    $stop7_addres=$N3_7[1];
                //N4
                    $stop7_city=$N4_7[1];
                    $stop7_state=$N4_7[2];
                    $stop7_postal_code=$N4_7[3];
                    $stop7_country=$N4_7[4];
            break;
            case "*8*":
                $S5_ocho = explode("~",$array[$i]);
                //Campos selecionados
                $S5_8 = explode("*",$S5_ocho[0]);
                $L11_8 = explode("*",$S5_ocho[1]);
                $G62_8 = explode("*",$S5_ocho[3]);
                $N1_8 = explode("*",$S5_ocho[5]);
                $N3_8 = explode("*",$S5_ocho[6]);
                $N4_8 = explode("*",$S5_ocho[7]);
                //S5
                    $stop8_reason_code=$S5_8[2];
                //L11
                    $stop8_tracking_number=$L11_8[1];
                //G62
                    $stop8_date=$G62_8[2];
                    $stop8_time=$G62_8[4];
                    $stop8_time_code=$G62_8[5];
                //N1
                    $stop8_identifier_code=$N1_8[1];
                    $stop8=$N1_8[2];
                //N3
                    $stop8_addres=$N3_8[1];
                //N4
                    $stop8_city=$N4_8[1];
                    $stop8_state=$N4_8[2];
                    $stop8_postal_code=$N4_8[3];
                    $stop8_country=$N4_8[4];
            break;
        }
    }

    //dd($S5_3);

    if($count == 4) {

        $data = array(
            'id_qualifier_sender' => $id_qualifier_sender,
            'id_sender' => $id_sender,
            'id_qualifier_receiver' => $id_qualifier_receiver,
            'id_receiver' => $id_receiver,
            'version_number' => $version_number,
            'control_number' => $control_number,
            'sender_code' => $sender_code,
            'agency_code' => $agency_code,
            'industry_identifier' => $industry_identifier,
            'control_number_sender' => $control_number_sender,
            'alpha_code' => $alpha_code,
            'shipment_identification_number' => $shipment_identification_number,
            'method_payment' => $method_payment,
            'purpose_code' => $purpose_code,
            'reference_identification' => $reference_identification,
            'reference_identification_qualifier' => $reference_identification_qualifier,
            //new stop1
            'stop1_tracking_number' => $stop1_tracking_number,
            'stop1_reason_code' => $stop1_reason_code,
            'stop1_identifier_code' => $stop1_identifier_code,
            'stop1' => $stop1,
            'stop1_addres' => $stop1_addres,
            'stop1_city' => $stop1_city,
            'stop1_postal_code' => $stop1_postal_code,
            'stop1_state' => $stop1_state,
            'stop1_country' => $stop1_country,
            'stop1_date' => $stop1_date,
            'stop1_time' => $stop1_time,
            'stop1_time_code' => $stop1_time_code,
            //new stop2
            'stop2_tracking_number' => $stop2_tracking_number,
            'stop2_reason_code' => $stop2_reason_code,
            'stop2_identifier_code' => $stop2_identifier_code,
            'stop2' => $stop2,
            'stop2_addres' => $stop2_addres,
            'stop2_city' => $stop2_city,
            'stop2_postal_code' => $stop2_postal_code,
            'stop2_state' => $stop2_state,
            'stop2_country' => $stop2_country,
            'stop2_date' => $stop2_date,
            'stop2_time' => $stop2_time,
            'stop2_time_code' => $stop2_time_code,
            //new stop3
            'stop3_tracking_number' => $stop3_tracking_number,
            'stop3_reason_code' => $stop3_reason_code,
            'stop3_identifier_code' => $stop3_identifier_code,
            'stop3' => $stop3,
            'stop3_addres' => $stop3_addres,
            'stop3_city' => $stop3_city,
            'stop3_postal_code' => $stop3_postal_code,
            'stop3_state' => $stop3_state,
            'stop3_country' => $stop3_country,
            'stop3_date' => $stop3_date,
            'stop3_time' => $stop3_time,
            'stop3_time_code' => $stop3_time_code,
            //new stop4
            'stop4_tracking_number' => $stop4_tracking_number,
            'stop4_reason_code' => $stop4_reason_code,
            'stop4_identifier_code' => $stop4_identifier_code,
            'stop4' => $stop4,
            'stop4_addres' => $stop4_addres,
            'stop4_city' => $stop4_city,
            'stop4_postal_code' => $stop4_postal_code,
            'stop4_state' => $stop4_state,
            'stop4_country' => $stop4_country,
            'stop4_date' => $stop4_date,
            'stop4_time' => $stop4_time,
            'stop4_time_code' => $stop4_time_code,
            //new stop5
            'stop5_tracking_number' => $stop5_tracking_number,
            'stop5_reason_code' => $stop5_reason_code,
            'stop5_identifier_code' => $stop5_identifier_code,
            'stop5' => $stop5,
            'stop5_addres' => $stop5_addres,
            'stop5_city' => $stop5_city,
            'stop5_postal_code' => $stop5_postal_code,
            'stop5_state' => $stop5_state,
            'stop5_country' => $stop5_country,
            'stop5_date' => $stop5_date,
            'stop5_time' => $stop5_time,
            'stop5_time_code' => $stop5_time_code,
            //new stop6
            'stop6_tracking_number' => $stop6_tracking_number,
            'stop6_reason_code' => $stop6_reason_code,
            'stop6_identifier_code' => $stop6_identifier_code,
            'stop6' => $stop6,
            'stop6_addres' => $stop6_addres,
            'stop6_city' => $stop6_city,
            'stop6_postal_code' => $stop6_postal_code,
            'stop6_state' => $stop6_state,
            'stop6_country' => $stop6_country,
            'stop6_date' => $stop6_date,
            'stop6_time' => $stop6_time,
            'stop6_time_code' => $stop6_time_code,
            );
    }

    dd($data);

    dd('test');

*/




        // $update05 = DB::connection('sqlsrvpro')->table("edi_daimler_204")->get();
        // dd($update05->all());

        // $status3 = DB::table('edidaimlers')->where('status', '3')->get();
        // dd($status3->all());

/*
        $path_file = 'Daimler/fromRyder/';
        $path_process = 'Daimler/fromRyder_process/';
        $path_store = 'Daimler/fromRyder_arch/';
        // archivos con stattus = 1 son nuevos estan en carpeta fromRyder
        $datafile = DB::table('edidaimlers')->where('status', '1')->get();
        if (count($datafile) ==! 0) { //validar existen datos
            foreach ($datafile as $file) {
                $fileid = $file->id;
                $filename = $file->filename;
                //mover a folder de proceso
                $procesar = Storage::move($path_file.$filename, $path_process.$filename);
                if (empty($procesar)) {
                    Log::warning('imposible mover archivo a proceso'.$filename);
                } else {
                    Log::info('se movio a proceso con exito: '.$filename);
                    // actualizar status archivo mysql
                    $update204 = EdiDaimler::findOrFail($fileid);
                    $update204->status = '2';
                        if ($update204->save()) {
                            Log::info('Estatus actualizado = 2: '. $fileid);
                            // grabar log en tabla nueva por crear
                            //
                            //
                        }
                        if ( $file->code == '204') {
                            $read_file = Storage::disk('local')->get($path_process.$filename);//lectura local del archivo 204
                            $array = explode("S5",$read_file); // sepracion por cada S5 que contiene
                            Log::info('Archivo separado en array S5');
                            $count = count($array);
                            for($i = 0; $i<$count; $i++){
                                switch(substr($array[$i],0,3))
                                {
                                case "ISA":
                                    $header = explode("~",$array[$i]);// Encabezado
                                    //Campos selecionados
                                    $ISA = explode("*",$header[0]);
                                    $GS = explode("*",$header[1]);
                                    $ST = explode("*",$header[2]);
                                    $B2 = explode("*",$header[3]);
                                    $B2A = explode("*",$header[4]);
                                    $L11 = explode("*",$header[7]);
                                    //ISA
                                        $id_qualifier_sender=$ISA[5];
                                        $id_sender=$ISA[6];
                                        $id_qualifier_receiver=$ISA[7];
                                        $id_receiver=$ISA[8];
                                        $version_number=$ISA[11];
                                        $control_number=$ISA[12];
                                    //GS
                                        $sender_code=$GS[2];
                                        $agency_code=$GS[7];
                                        $industry_identifier=$GS[8];
                                    //ST
                                        $control_number_sender=$ST[2];
                                    //B2
                                        $alpha_code=$B2[2];
                                        $shipment_identification_number=$B2[4];
                                        $method_payment=$B2[6];
                                    //B2A
                                        $purpose_code = $B2A[1];
                                    //L11
                                        $reference_identification=$L11[1];
                                        $reference_identification_qualifier=$L11[2];
                                break;                        
                                case "*1*":
                                    $S5_uno = explode("~",$array[$i]); // S5 inicial
                                    //Campos selecionados
                                    $S5_1 = explode("*",$S5_uno[0]);
                                    $L11_1 = explode("*",$S5_uno[1]);
                                    $G62_1 = explode("*",$S5_uno[3]);
                                    $N1_1 = explode("*",$S5_uno[5]);
                                    $N3_1 = explode("*",$S5_uno[6]);
                                    $N4_1 = explode("*",$S5_uno[7]);
                                    //S5
                                        $stop1_reason_code=$S5_1[2];
                                    //L11
                                        $stop1_tracking_number=$L11_1[1];
                                    //G62
                                        $stop1_date=$G62_1[2];
                                        $stop1_time=$G62_1[4];
                                        $stop1_time_code=$G62_1[5];
                                    //N1
                                        $stop1_identifier_code=$N1_1[1];
                                        $stop1=$N1_1[2];
                                    //N3
                                        $stop1_addres=$N3_1[1];
                                    //N4
                                        $stop1_city=$N4_1[1];
                                        $stop1_state=$N4_1[2];
                                        $stop1_postal_code=$N4_1[3];
                                        $stop1_country=$N4_1[4];
                                break;
                                case "*2*":
                                    $S5_dos = explode("~",$array[$i]);
                                    //Campos selecionados
                                    $S5_2 = explode("*",$S5_dos[0]);
                                    $L11_2 = explode("*",$S5_dos[1]);
                                    $G62_2 = explode("*",$S5_dos[3]);
                                    $N1_2 = explode("*",$S5_dos[5]);
                                    $N3_2 = explode("*",$S5_dos[6]);
                                    $N4_2 = explode("*",$S5_dos[7]);
                                    //S5
                                        $stop2_reason_code=$S5_2[2];
                                    //L11
                                        $stop2_tracking_number=$L11_2[1];
                                    //G62
                                        $stop2_date=$G62_2[2];
                                        $stop2_time=$G62_2[4];
                                        $stop2_time_code=$G62_2[5];
                                    //N1
                                        $stop2_identifier_code=$N1_2[1];
                                        $stop2=$N1_2[2];
                                    //N3
                                        $stop2_addres=$N3_2[1];
                                    //N4
                                        $stop2_city=$N4_2[1];
                                        $stop2_state=$N4_2[2];
                                        $stop2_postal_code=$N4_2[3];
                                        $stop2_country=$N4_2[4];
                                break;
                                case "*3*":
                                    $S5_tres = explode("~",$array[$i]);
                                    //Campos selecionados
                                    $S5_3 = explode("*",$S5_tres[0]);
                                    $L11_3 = explode("*",$S5_tres[1]);
                                    $G62_3 = explode("*",$S5_tres[3]);
                                    $N1_3 = explode("*",$S5_tres[5]);
                                    $N3_3 = explode("*",$S5_tres[6]);
                                    $N4_3 = explode("*",$S5_tres[7]);
                                    //S5
                                        $stop3_reason_code=$S5_3[2];
                                    //L11
                                        $stop3_tracking_number=$L11_3[1];
                                    //G62
                                        $stop3_date=$G62_3[2];
                                        $stop3_time=$G62_3[4];
                                        $stop3_time_code=$G62_3[5];
                                    //N1
                                        $stop3_identifier_code=$N1_3[1];
                                        $stop3=$N1_3[2];
                                    //N3
                                        $stop3_addres=$N3_3[1];
                                    //N4
                                        $stop3_city=$N4_3[1];
                                        $stop3_state=$N4_3[2];
                                        $stop3_postal_code=$N4_3[3];
                                        $stop3_country=$N4_3[4];
                                break;
                                case "*4*":
                                    $S5_cuatro = explode("~",$array[$i]);
                                    //Campos selecionados
                                    $S5_4 = explode("*",$S5_cuatro[0]);
                                    $L11_4 = explode("*",$S5_cuatro[1]);
                                    $G62_4 = explode("*",$S5_cuatro[3]);
                                    $N1_4 = explode("*",$S5_cuatro[5]);
                                    $N3_4 = explode("*",$S5_cuatro[6]);
                                    $N4_4 = explode("*",$S5_cuatro[7]);
                                    //S5
                                        $stop4_reason_code=$S5_4[2];
                                    //L11
                                        $stop4_tracking_number=$L11_4[1];
                                    //G62
                                        $stop4_date=$G62_4[2];
                                        $stop4_time=$G62_4[4];
                                        $stop4_time_code=$G62_4[5];
                                    //N1
                                        $stop4_identifier_code=$N1_4[1];
                                        $stop4=$N1_4[2];
                                    //N3
                                        $stop4_addres=$N3_4[1];
                                    //N4
                                        $stop4_city=$N4_4[1];
                                        $stop4_state=$N4_4[2];
                                        $stop4_postal_code=$N4_4[3];
                                        $stop4_country=$N4_4[4];
                                break;
                                case "*5*":
                                    $S5_cinco = explode("~",$array[$i]);
                                    //Campos selecionados
                                    $S5_5 = explode("*",$S5_cinco[0]);
                                    $L11_5 = explode("*",$S5_cinco[1]);
                                    $G62_5 = explode("*",$S5_cinco[3]);
                                    $N1_5 = explode("*",$S5_cinco[5]);
                                    $N3_5 = explode("*",$S5_cinco[6]);
                                    $N4_5 = explode("*",$S5_cinco[7]);
                                    //S5
                                        $stop5_reason_code=$S5_5[2];
                                    //L11
                                        $stop5_tracking_number=$L11_5[1];
                                    //G62
                                        $stop5_date=$G62_5[2];
                                        $stop5_time=$G62_5[4];
                                        $stop5_time_code=$G62_5[5];
                                    //N1
                                        $stop5_identifier_code=$N1_5[1];
                                        $stop5=$N1_5[2];
                                    //N3
                                        $stop5_addres=$N3_5[1];
                                    //N4
                                        $stop5_city=$N4_5[1];
                                        $stop5_state=$N4_5[2];
                                        $stop5_postal_code=$N4_5[3];
                                        $stop5_country=$N4_5[4];
                                break;
                                case "*6*":
                                    $S5_seis = explode("~",$array[$i]);
                                    //Campos selecionados
                                    $S5_6 = explode("*",$S5_seis[0]);
                                    $L11_6 = explode("*",$S5_seis[1]);
                                    $G62_6 = explode("*",$S5_seis[3]);
                                    $N1_6 = explode("*",$S5_seis[5]);
                                    $N3_6 = explode("*",$S5_seis[6]);
                                    $N4_6 = explode("*",$S5_seis[7]);
                                    //S5
                                        $stop6_reason_code=$S5_6[2];
                                    //L11
                                        $stop6_tracking_number=$L11_6[1];
                                    //G62
                                        $stop6_date=$G62_6[2];
                                        $stop6_time=$G62_6[4];
                                        $stop6_time_code=$G62_6[5];
                                    //N1
                                        $stop6_identifier_code=$N1_6[1];
                                        $stop6=$N1_6[2];
                                    //N3
                                        $stop6_addres=$N3_6[1];
                                    //N4
                                        $stop6_city=$N4_6[1];
                                        $stop6_state=$N4_6[2];
                                        $stop6_postal_code=$N4_6[3];
                                        $stop6_country=$N4_6[4];
                                break;
                                case "*7*":
                                    $S5_siete = explode("~",$array[$i]);
                                    //Campos selecionados
                                    $S5_7 = explode("*",$S5_siete[0]);
                                    $L11_7 = explode("*",$S5_siete[1]);
                                    $G62_7 = explode("*",$S5_siete[3]);
                                    $N1_7 = explode("*",$S5_siete[5]);
                                    $N3_7 = explode("*",$S5_siete[6]);
                                    $N4_7 = explode("*",$S5_siete[7]);
                                    //S5
                                        $stop7_reason_code=$S5_7[2];
                                    //L11
                                        $stop7_tracking_number=$L11_7[1];
                                    //G62
                                        $stop7_date=$G62_7[2];
                                        $stop7_time=$G62_7[4];
                                        $stop7_time_code=$G62_7[5];
                                    //N1
                                        $stop7_identifier_code=$N1_7[1];
                                        $stop7=$N1_7[2];
                                    //N3
                                        $stop7_addres=$N3_7[1];
                                    //N4
                                        $stop7_city=$N4_7[1];
                                        $stop7_state=$N4_7[2];
                                        $stop7_postal_code=$N4_7[3];
                                        $stop7_country=$N4_7[4];
                                break;
                                case "*8*":
                                    $S5_ocho = explode("~",$array[$i]);
                                    //Campos selecionados
                                    $S5_8 = explode("*",$S5_ocho[0]);
                                    $L11_8 = explode("*",$S5_ocho[1]);
                                    $G62_8 = explode("*",$S5_ocho[3]);
                                    $N1_8 = explode("*",$S5_ocho[5]);
                                    $N3_8 = explode("*",$S5_ocho[6]);
                                    $N4_8 = explode("*",$S5_ocho[7]);
                                    //S5
                                        $stop8_reason_code=$S5_8[2];
                                    //L11
                                        $stop8_tracking_number=$L11_8[1];
                                    //G62
                                        $stop8_date=$G62_8[2];
                                        $stop8_time=$G62_8[4];
                                        $stop8_time_code=$G62_8[5];
                                    //N1
                                        $stop8_identifier_code=$N1_8[1];
                                        $stop8=$N1_8[2];
                                    //N3
                                        $stop8_addres=$N3_8[1];
                                    //N4
                                        $stop8_city=$N4_8[1];
                                        $stop8_state=$N4_8[2];
                                        $stop8_postal_code=$N4_8[3];
                                        $stop8_country=$N4_8[4];
                                break;
                            }
                        }
                    // variable para las clases
                    $shipment_id = $shipment_identification_number;
                    if ($purpose_code == '00'){ //se procesa por primera vez
                        //almacenar en mysql
                        $data204 = EdiDaimler::findOrFail($fileid);
                        $data204->shipment_id = $shipment_identification_number;
                        $data204->purpose_code = $purpose_code;
                        $data204->s5total = $count-1;
                        if ($data204->save()) {
                            Log::info('Archivo actualizado Mysql');
                        // almacenar en BD Sqlsrv segun la cantidad de S5 contenida en txt
                            if ($count == 3) {
                                $destino = $stop2;
                                $save204 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->insert([
                                    'id_qualifier_sender' => $id_qualifier_sender,
                                    'id_sender' => $id_sender,
                                    'id_qualifier_receiver' => $id_qualifier_receiver,
                                    'id_receiver' => $id_receiver,
                                    'version_number' => $version_number,
                                    'control_number' => $control_number,
                                    'sender_code' => $sender_code,
                                    'agency_code' => $agency_code,
                                    'industry_identifier' => $industry_identifier,
                                    'control_number_sender' => $control_number_sender,
                                    'alpha_code' => $alpha_code,
                                    'shipment_identification_number' => $shipment_identification_number,
                                    'method_payment' => $method_payment,
                                    'purpose_code' => $purpose_code,
                                    'reference_identification' => $reference_identification,
                                    'reference_identification_qualifier' => $reference_identification_qualifier,
                                    //new stop1
                                    'stop1_tracking_number' => $stop1_tracking_number,
                                    'stop1_reason_code' => $stop1_reason_code,
                                    'stop1_identifier_code' => $stop1_identifier_code,
                                    'stop1' => $stop1,
                                    'stop1_addres' => $stop1_addres,
                                    'stop1_city' => $stop1_city,
                                    'stop1_postal_code' => $stop1_postal_code,
                                    'stop1_state' => $stop1_state,
                                    'stop1_country' => $stop1_country,
                                    'stop1_date' => $stop1_date,
                                    'stop1_time' => $stop1_time,
                                    'stop1_time_code' => $stop1_time_code,
                                    //new stop2
                                    'stop2_tracking_number' => $stop2_tracking_number,
                                    'stop2_reason_code' => $stop2_reason_code,
                                    'stop2_identifier_code' => $stop2_identifier_code,
                                    'stop2' => $stop2,
                                    'stop2_addres' => $stop2_addres,
                                    'stop2_city' => $stop2_city,
                                    'stop2_postal_code' => $stop2_postal_code,
                                    'stop2_state' => $stop2_state,
                                    'stop2_country' => $stop2_country,
                                    'stop2_date' => $stop2_date,
                                    'stop2_time' => $stop2_time,
                                    'stop2_time_code' => $stop2_time_code,
                                    'stops' => $count-1]);
                            } elseif($count == 4) {
                                $destino = $stop3;
                                $save204 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->insert([
                                    'id_qualifier_sender' => $id_qualifier_sender,
                                    'id_sender' => $id_sender,
                                    'id_qualifier_receiver' => $id_qualifier_receiver,
                                    'id_receiver' => $id_receiver,
                                    'version_number' => $version_number,
                                    'control_number' => $control_number,
                                    'sender_code' => $sender_code,
                                    'agency_code' => $agency_code,
                                    'industry_identifier' => $industry_identifier,
                                    'control_number_sender' => $control_number_sender,
                                    'alpha_code' => $alpha_code,
                                    'shipment_identification_number' => $shipment_identification_number,
                                    'method_payment' => $method_payment,
                                    'purpose_code' => $purpose_code,
                                    'reference_identification' => $reference_identification,
                                    'reference_identification_qualifier' => $reference_identification_qualifier,
                                    //new stop1
                                    'stop1_tracking_number' => $stop1_tracking_number,
                                    'stop1_reason_code' => $stop1_reason_code,
                                    'stop1_identifier_code' => $stop1_identifier_code,
                                    'stop1' => $stop1,
                                    'stop1_addres' => $stop1_addres,
                                    'stop1_city' => $stop1_city,
                                    'stop1_postal_code' => $stop1_postal_code,
                                    'stop1_state' => $stop1_state,
                                    'stop1_country' => $stop1_country,
                                    'stop1_date' => $stop1_date,
                                    'stop1_time' => $stop1_time,
                                    'stop1_time_code' => $stop1_time_code,
                                    //new stop2
                                    'stop2_tracking_number' => $stop2_tracking_number,
                                    'stop2_reason_code' => $stop2_reason_code,
                                    'stop2_identifier_code' => $stop2_identifier_code,
                                    'stop2' => $stop2,
                                    'stop2_addres' => $stop2_addres,
                                    'stop2_city' => $stop2_city,
                                    'stop2_postal_code' => $stop2_postal_code,
                                    'stop2_state' => $stop2_state,
                                    'stop2_country' => $stop2_country,
                                    'stop2_date' => $stop2_date,
                                    'stop2_time' => $stop2_time,
                                    'stop2_time_code' => $stop2_time_code,
                                    //new stop3
                                    'stop3_tracking_number' => $stop3_tracking_number,
                                    'stop3_reason_code' => $stop3_reason_code,
                                    'stop3_identifier_code' => $stop3_identifier_code,
                                    'stop3' => $stop3,
                                    'stop3_addres' => $stop3_addres,
                                    'stop3_city' => $stop3_city,
                                    'stop3_postal_code' => $stop3_postal_code,
                                    'stop3_state' => $stop3_state,
                                    'stop3_country' => $stop3_country,
                                    'stop3_date' => $stop3_date,
                                    'stop3_time' => $stop3_time,
                                    'stop3_time_code' => $stop3_time_code,
                                    'stops' => $count-1]);
                            } elseif($count == 5) {
                                $destino = $stop4;
                                $save204 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->insert([
                                    'id_qualifier_sender' => $id_qualifier_sender,
                                    'id_sender' => $id_sender,
                                    'id_qualifier_receiver' => $id_qualifier_receiver,
                                    'id_receiver' => $id_receiver,
                                    'version_number' => $version_number,
                                    'control_number' => $control_number,
                                    'sender_code' => $sender_code,
                                    'agency_code' => $agency_code,
                                    'industry_identifier' => $industry_identifier,
                                    'control_number_sender' => $control_number_sender,
                                    'alpha_code' => $alpha_code,
                                    'shipment_identification_number' => $shipment_identification_number,
                                    'method_payment' => $method_payment,
                                    'purpose_code' => $purpose_code,
                                    'reference_identification' => $reference_identification,
                                    'reference_identification_qualifier' => $reference_identification_qualifier,
                                    //new stop1
                                    'stop1_tracking_number' => $stop1_tracking_number,
                                    'stop1_reason_code' => $stop1_reason_code,
                                    'stop1_identifier_code' => $stop1_identifier_code,
                                    'stop1' => $stop1,
                                    'stop1_addres' => $stop1_addres,
                                    'stop1_city' => $stop1_city,
                                    'stop1_postal_code' => $stop1_postal_code,
                                    'stop1_state' => $stop1_state,
                                    'stop1_country' => $stop1_country,
                                    'stop1_date' => $stop1_date,
                                    'stop1_time' => $stop1_time,
                                    'stop1_time_code' => $stop1_time_code,
                                    //new stop2
                                    'stop2_tracking_number' => $stop2_tracking_number,
                                    'stop2_reason_code' => $stop2_reason_code,
                                    'stop2_identifier_code' => $stop2_identifier_code,
                                    'stop2' => $stop2,
                                    'stop2_addres' => $stop2_addres,
                                    'stop2_city' => $stop2_city,
                                    'stop2_postal_code' => $stop2_postal_code,
                                    'stop2_state' => $stop2_state,
                                    'stop2_country' => $stop2_country,
                                    'stop2_date' => $stop2_date,
                                    'stop2_time' => $stop2_time,
                                    'stop2_time_code' => $stop2_time_code,
                                    //new stop3
                                    'stop3_tracking_number' => $stop3_tracking_number,
                                    'stop3_reason_code' => $stop3_reason_code,
                                    'stop3_identifier_code' => $stop3_identifier_code,
                                    'stop3' => $stop3,
                                    'stop3_addres' => $stop3_addres,
                                    'stop3_city' => $stop3_city,
                                    'stop3_postal_code' => $stop3_postal_code,
                                    'stop3_state' => $stop3_state,
                                    'stop3_country' => $stop3_country,
                                    'stop3_date' => $stop3_date,
                                    'stop3_time' => $stop3_time,
                                    'stop3_time_code' => $stop3_time_code,
                                    //new stop4
                                    'stop4_tracking_number' => $stop4_tracking_number,
                                    'stop4_reason_code' => $stop4_reason_code,
                                    'stop4_identifier_code' => $stop4_identifier_code,
                                    'stop4' => $stop4,
                                    'stop4_addres' => $stop4_addres,
                                    'stop4_city' => $stop4_city,
                                    'stop4_postal_code' => $stop4_postal_code,
                                    'stop4_state' => $stop4_state,
                                    'stop4_country' => $stop4_country,
                                    'stop4_date' => $stop4_date,
                                    'stop4_time' => $stop4_time,
                                    'stop4_time_code' => $stop4_time_code,
                                    'stops' => $count-1]);
                            } elseif($count == 6) {
                                $destino = $stop5;
                                $save204 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->insert([
                                    'id_qualifier_sender' => $id_qualifier_sender,
                                    'id_sender' => $id_sender,
                                    'id_qualifier_receiver' => $id_qualifier_receiver,
                                    'id_receiver' => $id_receiver,
                                    'version_number' => $version_number,
                                    'control_number' => $control_number,
                                    'sender_code' => $sender_code,
                                    'agency_code' => $agency_code,
                                    'industry_identifier' => $industry_identifier,
                                    'control_number_sender' => $control_number_sender,
                                    'alpha_code' => $alpha_code,
                                    'shipment_identification_number' => $shipment_identification_number,
                                    'method_payment' => $method_payment,
                                    'purpose_code' => $purpose_code,
                                    'reference_identification' => $reference_identification,
                                    'reference_identification_qualifier' => $reference_identification_qualifier,
                                    //new stop1
                                    'stop1_tracking_number' => $stop1_tracking_number,
                                    'stop1_reason_code' => $stop1_reason_code,
                                    'stop1_identifier_code' => $stop1_identifier_code,
                                    'stop1' => $stop1,
                                    'stop1_addres' => $stop1_addres,
                                    'stop1_city' => $stop1_city,
                                    'stop1_postal_code' => $stop1_postal_code,
                                    'stop1_state' => $stop1_state,
                                    'stop1_country' => $stop1_country,
                                    'stop1_date' => $stop1_date,
                                    'stop1_time' => $stop1_time,
                                    'stop1_time_code' => $stop1_time_code,
                                    //new stop2
                                    'stop2_tracking_number' => $stop2_tracking_number,
                                    'stop2_reason_code' => $stop2_reason_code,
                                    'stop2_identifier_code' => $stop2_identifier_code,
                                    'stop2' => $stop2,
                                    'stop2_addres' => $stop2_addres,
                                    'stop2_city' => $stop2_city,
                                    'stop2_postal_code' => $stop2_postal_code,
                                    'stop2_state' => $stop2_state,
                                    'stop2_country' => $stop2_country,
                                    'stop2_date' => $stop2_date,
                                    'stop2_time' => $stop2_time,
                                    'stop2_time_code' => $stop2_time_code,
                                    //new stop3
                                    'stop3_tracking_number' => $stop3_tracking_number,
                                    'stop3_reason_code' => $stop3_reason_code,
                                    'stop3_identifier_code' => $stop3_identifier_code,
                                    'stop3' => $stop3,
                                    'stop3_addres' => $stop3_addres,
                                    'stop3_city' => $stop3_city,
                                    'stop3_postal_code' => $stop3_postal_code,
                                    'stop3_state' => $stop3_state,
                                    'stop3_country' => $stop3_country,
                                    'stop3_date' => $stop3_date,
                                    'stop3_time' => $stop3_time,
                                    'stop3_time_code' => $stop3_time_code,
                                    //new stop4
                                    'stop4_tracking_number' => $stop4_tracking_number,
                                    'stop4_reason_code' => $stop4_reason_code,
                                    'stop4_identifier_code' => $stop4_identifier_code,
                                    'stop4' => $stop4,
                                    'stop4_addres' => $stop4_addres,
                                    'stop4_city' => $stop4_city,
                                    'stop4_postal_code' => $stop4_postal_code,
                                    'stop4_state' => $stop4_state,
                                    'stop4_country' => $stop4_country,
                                    'stop4_date' => $stop4_date,
                                    'stop4_time' => $stop4_time,
                                    'stop4_time_code' => $stop4_time_code,
                                    //new stop5
                                    'stop5_tracking_number' => $stop5_tracking_number,
                                    'stop5_reason_code' => $stop5_reason_code,
                                    'stop5_identifier_code' => $stop5_identifier_code,
                                    'stop5' => $stop5,
                                    'stop5_addres' => $stop5_addres,
                                    'stop5_city' => $stop5_city,
                                    'stop5_postal_code' => $stop5_postal_code,
                                    'stop5_state' => $stop5_state,
                                    'stop5_country' => $stop5_country,
                                    'stop5_date' => $stop5_date,
                                    'stop5_time' => $stop5_time,
                                    'stop5_time_code' => $stop5_time_code,
                                    'stops' => $count-1]);
                            } elseif($count == 7) {
                                $destino = $stop6;
                                $save204 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->insert([
                                    'id_qualifier_sender' => $id_qualifier_sender,
                                    'id_sender' => $id_sender,
                                    'id_qualifier_receiver' => $id_qualifier_receiver,
                                    'id_receiver' => $id_receiver,
                                    'version_number' => $version_number,
                                    'control_number' => $control_number,
                                    'sender_code' => $sender_code,
                                    'agency_code' => $agency_code,
                                    'industry_identifier' => $industry_identifier,
                                    'control_number_sender' => $control_number_sender,
                                    'alpha_code' => $alpha_code,
                                    'shipment_identification_number' => $shipment_identification_number,
                                    'method_payment' => $method_payment,
                                    'purpose_code' => $purpose_code,
                                    'reference_identification' => $reference_identification,
                                    'reference_identification_qualifier' => $reference_identification_qualifier,
                                    //new stop1
                                    'stop1_tracking_number' => $stop1_tracking_number,
                                    'stop1_reason_code' => $stop1_reason_code,
                                    'stop1_identifier_code' => $stop1_identifier_code,
                                    'stop1' => $stop1,
                                    'stop1_addres' => $stop1_addres,
                                    'stop1_city' => $stop1_city,
                                    'stop1_postal_code' => $stop1_postal_code,
                                    'stop1_state' => $stop1_state,
                                    'stop1_country' => $stop1_country,
                                    'stop1_date' => $stop1_date,
                                    'stop1_time' => $stop1_time,
                                    'stop1_time_code' => $stop1_time_code,
                                    //new stop2
                                    'stop2_tracking_number' => $stop2_tracking_number,
                                    'stop2_reason_code' => $stop2_reason_code,
                                    'stop2_identifier_code' => $stop2_identifier_code,
                                    'stop2' => $stop2,
                                    'stop2_addres' => $stop2_addres,
                                    'stop2_city' => $stop2_city,
                                    'stop2_postal_code' => $stop2_postal_code,
                                    'stop2_state' => $stop2_state,
                                    'stop2_country' => $stop2_country,
                                    'stop2_date' => $stop2_date,
                                    'stop2_time' => $stop2_time,
                                    'stop2_time_code' => $stop2_time_code,
                                    //new stop3
                                    'stop3_tracking_number' => $stop3_tracking_number,
                                    'stop3_reason_code' => $stop3_reason_code,
                                    'stop3_identifier_code' => $stop3_identifier_code,
                                    'stop3' => $stop3,
                                    'stop3_addres' => $stop3_addres,
                                    'stop3_city' => $stop3_city,
                                    'stop3_postal_code' => $stop3_postal_code,
                                    'stop3_state' => $stop3_state,
                                    'stop3_country' => $stop3_country,
                                    'stop3_date' => $stop3_date,
                                    'stop3_time' => $stop3_time,
                                    'stop3_time_code' => $stop3_time_code,
                                    //new stop4
                                    'stop4_tracking_number' => $stop4_tracking_number,
                                    'stop4_reason_code' => $stop4_reason_code,
                                    'stop4_identifier_code' => $stop4_identifier_code,
                                    'stop4' => $stop4,
                                    'stop4_addres' => $stop4_addres,
                                    'stop4_city' => $stop4_city,
                                    'stop4_postal_code' => $stop4_postal_code,
                                    'stop4_state' => $stop4_state,
                                    'stop4_country' => $stop4_country,
                                    'stop4_date' => $stop4_date,
                                    'stop4_time' => $stop4_time,
                                    'stop4_time_code' => $stop4_time_code,
                                    //new stop5
                                    'stop5_tracking_number' => $stop5_tracking_number,
                                    'stop5_reason_code' => $stop5_reason_code,
                                    'stop5_identifier_code' => $stop5_identifier_code,
                                    'stop5' => $stop5,
                                    'stop5_addres' => $stop5_addres,
                                    'stop5_city' => $stop5_city,
                                    'stop5_postal_code' => $stop5_postal_code,
                                    'stop5_state' => $stop5_state,
                                    'stop5_country' => $stop5_country,
                                    'stop5_date' => $stop5_date,
                                    'stop5_time' => $stop5_time,
                                    'stop5_time_code' => $stop5_time_code,
                                    //new stop6
                                    'stop6_tracking_number' => $stop6_tracking_number,
                                    'stop6_reason_code' => $stop6_reason_code,
                                    'stop6_identifier_code' => $stop6_identifier_code,
                                    'stop6' => $stop6,
                                    'stop6_addres' => $stop6_addres,
                                    'stop6_city' => $stop6_city,
                                    'stop6_postal_code' => $stop6_postal_code,
                                    'stop6_state' => $stop6_state,
                                    'stop6_country' => $stop6_country,
                                    'stop6_date' => $stop6_date,
                                    'stop6_time' => $stop6_time,
                                    'stop6_time_code' => $stop6_time_code,
                                    'stops' => $count-1]);
                            } elseif($count == 8) {
                                $destino = $stop7;
                                $save204 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->insert([
                                    'id_qualifier_sender' => $id_qualifier_sender,
                                    'id_sender' => $id_sender,
                                    'id_qualifier_receiver' => $id_qualifier_receiver,
                                    'id_receiver' => $id_receiver,
                                    'version_number' => $version_number,
                                    'control_number' => $control_number,
                                    'sender_code' => $sender_code,
                                    'agency_code' => $agency_code,
                                    'industry_identifier' => $industry_identifier,
                                    'control_number_sender' => $control_number_sender,
                                    'alpha_code' => $alpha_code,
                                    'shipment_identification_number' => $shipment_identification_number,
                                    'method_payment' => $method_payment,
                                    'purpose_code' => $purpose_code,
                                    'reference_identification' => $reference_identification,
                                    'reference_identification_qualifier' => $reference_identification_qualifier,
                                    //new stop1
                                    'stop1_tracking_number' => $stop1_tracking_number,
                                    'stop1_reason_code' => $stop1_reason_code,
                                    'stop1_identifier_code' => $stop1_identifier_code,
                                    'stop1' => $stop1,
                                    'stop1_addres' => $stop1_addres,
                                    'stop1_city' => $stop1_city,
                                    'stop1_postal_code' => $stop1_postal_code,
                                    'stop1_state' => $stop1_state,
                                    'stop1_country' => $stop1_country,
                                    'stop1_date' => $stop1_date,
                                    'stop1_time' => $stop1_time,
                                    'stop1_time_code' => $stop1_time_code,
                                    //new stop2
                                    'stop2_tracking_number' => $stop2_tracking_number,
                                    'stop2_reason_code' => $stop2_reason_code,
                                    'stop2_identifier_code' => $stop2_identifier_code,
                                    'stop2' => $stop2,
                                    'stop2_addres' => $stop2_addres,
                                    'stop2_city' => $stop2_city,
                                    'stop2_postal_code' => $stop2_postal_code,
                                    'stop2_state' => $stop2_state,
                                    'stop2_country' => $stop2_country,
                                    'stop2_date' => $stop2_date,
                                    'stop2_time' => $stop2_time,
                                    'stop2_time_code' => $stop2_time_code,
                                    //new stop3
                                    'stop3_tracking_number' => $stop3_tracking_number,
                                    'stop3_reason_code' => $stop3_reason_code,
                                    'stop3_identifier_code' => $stop3_identifier_code,
                                    'stop3' => $stop3,
                                    'stop3_addres' => $stop3_addres,
                                    'stop3_city' => $stop3_city,
                                    'stop3_postal_code' => $stop3_postal_code,
                                    'stop3_state' => $stop3_state,
                                    'stop3_country' => $stop3_country,
                                    'stop3_date' => $stop3_date,
                                    'stop3_time' => $stop3_time,
                                    'stop3_time_code' => $stop3_time_code,
                                    //new stop4
                                    'stop4_tracking_number' => $stop4_tracking_number,
                                    'stop4_reason_code' => $stop4_reason_code,
                                    'stop4_identifier_code' => $stop4_identifier_code,
                                    'stop4' => $stop4,
                                    'stop4_addres' => $stop4_addres,
                                    'stop4_city' => $stop4_city,
                                    'stop4_postal_code' => $stop4_postal_code,
                                    'stop4_state' => $stop4_state,
                                    'stop4_country' => $stop4_country,
                                    'stop4_date' => $stop4_date,
                                    'stop4_time' => $stop4_time,
                                    'stop4_time_code' => $stop4_time_code,
                                    //new stop5
                                    'stop5_tracking_number' => $stop5_tracking_number,
                                    'stop5_reason_code' => $stop5_reason_code,
                                    'stop5_identifier_code' => $stop5_identifier_code,
                                    'stop5' => $stop5,
                                    'stop5_addres' => $stop5_addres,
                                    'stop5_city' => $stop5_city,
                                    'stop5_postal_code' => $stop5_postal_code,
                                    'stop5_state' => $stop5_state,
                                    'stop5_country' => $stop5_country,
                                    'stop5_date' => $stop5_date,
                                    'stop5_time' => $stop5_time,
                                    'stop5_time_code' => $stop5_time_code,
                                    //new stop6
                                    'stop6_tracking_number' => $stop6_tracking_number,
                                    'stop6_reason_code' => $stop6_reason_code,
                                    'stop6_identifier_code' => $stop6_identifier_code,
                                    'stop6' => $stop6,
                                    'stop6_addres' => $stop6_addres,
                                    'stop6_city' => $stop6_city,
                                    'stop6_postal_code' => $stop6_postal_code,
                                    'stop6_state' => $stop6_state,
                                    'stop6_country' => $stop6_country,
                                    'stop6_date' => $stop6_date,
                                    'stop6_time' => $stop6_time,
                                    'stop6_time_code' => $stop6_time_code,
                                    //new stop7
                                    'stop7_tracking_number' => $stop7_tracking_number,
                                    'stop7_reason_code' => $stop7_reason_code,
                                    'stop7_identifier_code' => $stop7_identifier_code,
                                    'stop7' => $stop7,
                                    'stop7_addres' => $stop7_addres,
                                    'stop7_city' => $stop7_city,
                                    'stop7_postal_code' => $stop7_postal_code,
                                    'stop7_state' => $stop7_state,
                                    'stop7_country' => $stop7_country,
                                    'stop7_date' => $stop7_date,
                                    'stop7_time' => $stop7_time,
                                    'stop7_time_code' => $stop7_time_code,
                                    'stops' => $count-1]);
                            } elseif($count == 9) {
                                $destino = $stop8;
                                $save204 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->insert([
                                    'id_qualifier_sender' => $id_qualifier_sender,
                                    'id_sender' => $id_sender,
                                    'id_qualifier_receiver' => $id_qualifier_receiver,
                                    'id_receiver' => $id_receiver,
                                    'version_number' => $version_number,
                                    'control_number' => $control_number,
                                    'sender_code' => $sender_code,
                                    'agency_code' => $agency_code,
                                    'industry_identifier' => $industry_identifier,
                                    'control_number_sender' => $control_number_sender,
                                    'alpha_code' => $alpha_code,
                                    'shipment_identification_number' => $shipment_identification_number,
                                    'method_payment' => $method_payment,
                                    'purpose_code' => $purpose_code,
                                    'reference_identification' => $reference_identification,
                                    'reference_identification_qualifier' => $reference_identification_qualifier,
                                    //new stop1
                                    'stop1_tracking_number' => $stop1_tracking_number,
                                    'stop1_reason_code' => $stop1_reason_code,
                                    'stop1_identifier_code' => $stop1_identifier_code,
                                    'stop1' => $stop1,
                                    'stop1_addres' => $stop1_addres,
                                    'stop1_city' => $stop1_city,
                                    'stop1_postal_code' => $stop1_postal_code,
                                    'stop1_state' => $stop1_state,
                                    'stop1_country' => $stop1_country,
                                    'stop1_date' => $stop1_date,
                                    'stop1_time' => $stop1_time,
                                    'stop1_time_code' => $stop1_time_code,
                                    //new stop2
                                    'stop2_tracking_number' => $stop2_tracking_number,
                                    'stop2_reason_code' => $stop2_reason_code,
                                    'stop2_identifier_code' => $stop2_identifier_code,
                                    'stop2' => $stop2,
                                    'stop2_addres' => $stop2_addres,
                                    'stop2_city' => $stop2_city,
                                    'stop2_postal_code' => $stop2_postal_code,
                                    'stop2_state' => $stop2_state,
                                    'stop2_country' => $stop2_country,
                                    'stop2_date' => $stop2_date,
                                    'stop2_time' => $stop2_time,
                                    'stop2_time_code' => $stop2_time_code,
                                    //new stop3
                                    'stop3_tracking_number' => $stop3_tracking_number,
                                    'stop3_reason_code' => $stop3_reason_code,
                                    'stop3_identifier_code' => $stop3_identifier_code,
                                    'stop3' => $stop3,
                                    'stop3_addres' => $stop3_addres,
                                    'stop3_city' => $stop3_city,
                                    'stop3_postal_code' => $stop3_postal_code,
                                    'stop3_state' => $stop3_state,
                                    'stop3_country' => $stop3_country,
                                    'stop3_date' => $stop3_date,
                                    'stop3_time' => $stop3_time,
                                    'stop3_time_code' => $stop3_time_code,
                                    //new stop4
                                    'stop4_tracking_number' => $stop4_tracking_number,
                                    'stop4_reason_code' => $stop4_reason_code,
                                    'stop4_identifier_code' => $stop4_identifier_code,
                                    'stop4' => $stop4,
                                    'stop4_addres' => $stop4_addres,
                                    'stop4_city' => $stop4_city,
                                    'stop4_postal_code' => $stop4_postal_code,
                                    'stop4_state' => $stop4_state,
                                    'stop4_country' => $stop4_country,
                                    'stop4_date' => $stop4_date,
                                    'stop4_time' => $stop4_time,
                                    'stop4_time_code' => $stop4_time_code,
                                    //new stop5
                                    'stop5_tracking_number' => $stop5_tracking_number,
                                    'stop5_reason_code' => $stop5_reason_code,
                                    'stop5_identifier_code' => $stop5_identifier_code,
                                    'stop5' => $stop5,
                                    'stop5_addres' => $stop5_addres,
                                    'stop5_city' => $stop5_city,
                                    'stop5_postal_code' => $stop5_postal_code,
                                    'stop5_state' => $stop5_state,
                                    'stop5_country' => $stop5_country,
                                    'stop5_date' => $stop5_date,
                                    'stop5_time' => $stop5_time,
                                    'stop5_time_code' => $stop5_time_code,
                                    //new stop6
                                    'stop6_tracking_number' => $stop6_tracking_number,
                                    'stop6_reason_code' => $stop6_reason_code,
                                    'stop6_identifier_code' => $stop6_identifier_code,
                                    'stop6' => $stop6,
                                    'stop6_addres' => $stop6_addres,
                                    'stop6_city' => $stop6_city,
                                    'stop6_postal_code' => $stop6_postal_code,
                                    'stop6_state' => $stop6_state,
                                    'stop6_country' => $stop6_country,
                                    'stop6_date' => $stop6_date,
                                    'stop6_time' => $stop6_time,
                                    'stop6_time_code' => $stop6_time_code,
                                    //new stop7
                                    'stop7_tracking_number' => $stop7_tracking_number,
                                    'stop7_reason_code' => $stop7_reason_code,
                                    'stop7_identifier_code' => $stop7_identifier_code,
                                    'stop7' => $stop7,
                                    'stop7_addres' => $stop7_addres,
                                    'stop7_city' => $stop7_city,
                                    'stop7_postal_code' => $stop7_postal_code,
                                    'stop7_state' => $stop7_state,
                                    'stop7_country' => $stop7_country,
                                    'stop7_date' => $stop7_date,
                                    'stop7_time' => $stop7_time,
                                    'stop7_time_code' => $stop7_time_code,
                                    //new stop8
                                    'stop8_tracking_number' => $stop8_tracking_number,
                                    'stop8_reason_code' => $stop8_reason_code,
                                    'stop8_identifier_code' => $stop8_identifier_code,
                                    'stop8' => $stop8,
                                    'stop8_addres' => $stop8_addres,
                                    'stop8_city' => $stop8_city,
                                    'stop8_postal_code' => $stop8_postal_code,
                                    'stop8_state' => $stop8_state,
                                    'stop8_country' => $stop8_country,
                                    'stop8_date' => $stop8_date,
                                    'stop8_time' => $stop8_time,
                                    'stop8_time_code' => $stop8_time_code,
                                    'stops' => $count-1]);
                            } else {
                                Log::error('No hubo coincidencias en Array Count(3,4,5,6,7,8)');
                            }
                            if (empty($save204)) { 
                                Log::warning('No se guardaron datos txt204 code 00 SqlSrv'); }
                                // grabar log en tabla nueva por crear
                                //
                                //
                            else {
                                Log::info('Datos almacenados en SqlSrv txt204 code 00');

                                $code = '0'; //para plantilla correo con markdown
                                $origen = $stop1;
                                $fecha = date('d/M/Y', strtotime($stop1_date));
                                $hora = date('H:i', strtotime($stop1_time));

                                //para envio de notificacion
                                $code00 = new edidaimler();
                                $code00->Notificacion($code, $shipment_id, $origen, $destino, $fecha, $hora);

                                // para confirmacion con el txt 997
                                $txt997 = new edidaimler();
                                $txt997->create997($shipment_id, $fileid, $filename);
                                }
                            } else {
                            Log::warning('fallo en actualizar archivo Mysql');
                            }
                        } elseif ($purpose_code == '05') { //Si es 05 Actualizar Fechas y notificar
                        $val = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where('shipment_identification_number', '=', $shipment_identification_number)->first();
                        if (empty($val)) {
                            Log::warning('No existen datos edi_daimler_204 con id: '.$shipment_identification_number);

                            //almacenar en mysql
                            $data204 = EdiDaimler::findOrFail($fileid);
                            $data204->shipment_id = $shipment_identification_number;
                            $data204->purpose_code = $purpose_code;
                            $data204->s5total = $count-1;
                            $data204->status = '3'; //para referencia Pendiente de actualizar
                            $data204->save();
                        } else {

                            //almacenar en mysql
                            $data204 = EdiDaimler::findOrFail($fileid);
                            $data204->shipment_id = $shipment_identification_number;
                            $data204->purpose_code = $purpose_code;
                            $data204->s5total = $count-1;
                                if ($data204->save()) {
                                    Log::info('Archivo actualizado Mysql');

                                    //actualizar fechas SqlSrv tabla edi_daimler_204 txt204
                                    $info05 = new edidaimler();
                                    $info05->code05($fileid);
                                }
                        }
                    } elseif ($purpose_code == '01') { //Si es 01 se Cancela pedido, actualizar y Notificar
                            $val01 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where('shipment_identification_number', '=', $shipment_identification_number)->first();
                            if (empty($val01)) {
                                Log::warning('No existen datos edi_daimler_204 con id: '.$shipment_identification_number);

                                //almacenar en mysql
                                $data204 = EdiDaimler::findOrFail($fileid);
                                $data204->shipment_id = $shipment_identification_number;
                                $data204->purpose_code = $purpose_code;
                                $data204->s5total = $count-1;
                                $data204->status = '3'; //para referencia Pendiente de actualizar
                                $data204->save();
                            } else {

                                //almacenar en mysql
                                $data204 = EdiDaimler::findOrFail($fileid);
                                $data204->shipment_id = $shipment_identification_number;
                                $data204->purpose_code = $purpose_code;
                                $data204->s5total = $count-1;
                                if ($data204->save()) {
                                Log::info('Archivo actualizado Mysql');

                                // actualizar tabla edi_daimler_204
                                $info01 = new edidaimler;
                                $info01->code01($fileid, $shipment_id, $filename);
                            }
                        }
                    } else {
                        Log::warning('Archivo 204: purpose_code desconocido');
                    }
                } elseif ( $file->code == '824') {
                    $read_file824 = Storage::disk('local')->get($path_process.$filename);//lectura local del archivo 824
                    $array824 = explode("~",$read_file824); // sepracion por cada S5 que contiene
                    Log::info('Archivo 824 separado en array ~');
                    $count824 = count($array824);
                        for($i = 0; $i<$count824; $i++) {
                            $data824=explode("*",$array824[$i]);
                            switch(substr($array824[$i],0,3)) {
                                case 'GS*':
                                    $GS_date=$data824[4];
                                    $GS_time=$data824[5];
                                break;
                                case 'OTI':
                                    $OTI_reference_identification=$data824[3];
                                break;
                                case 'REF':
                                    $REF_shipment_identification_number=$data824[2];
                                break;
                                case 'TED':
                                    $TED_error_code=$data824[1];
                                    $TED_message=$data824[2];
                                break;
                            }
                        }
                    $datetime = date('Ymd H:i:s', strtotime($GS_date.$GS_time));
                    //almacenar en mysql
                    $data824 = EdiDaimler::findOrFail($fileid);
                    $data824->shipment_id = $REF_shipment_identification_number; //valida que tenga el datos
                    if ($data824->save()) {
                        Log::info('Archivo 824 actualizado Mysql');
                    //almacenar en SqlSrv
                    $save824 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_824")->insert([
                        'shipment_identification_number' => $REF_shipment_identification_number,
                        'reference_identification' => $OTI_reference_identification,
                        'error_code' => $TED_error_code,
                        'message' => $TED_message,
                        'date' => $datetime,
                        'time' => $datetime,
                        'send_txt' => '1' ]);
                        if (empty($save824)) { Log::warning('No se guardaron datos de txt 824 SqlSrv'); }
                        else { 
                            Log::info('Datos Edi 824 almacenados en SqlSrv!');

                            $code = '824'; //es para usar la plantilla correo con markdown
                            $id = $REF_shipment_identification_number;
                            $origen = $TED_error_code;
                            $destino = $TED_message;
                            $fecha = 'null'; //date('d/M/Y', strtotime($G6202_load_date_1));
                            $hora = 'null'; //date('H:i', strtotime($G6204_load_time_1));
                            $email = env('MAIL_SEND_DAIMLER');
                            $ccmails = env('CCMAIL_SEND_DAIMLER');
                            Mail::to($email)->cc($ccmails)->send(new NotificaDaimler($code, $id, $origen, $destino, $fecha, $hora));
                                Log::info('Notificacion edi 824 Correo enviado!');

                            // actualizar status archivo mysql
                            $update824 = EdiDaimler::findOrFail($fileid);
                            $update824->status = '0';
                            if ($update824->save()) {
                                Log::info('Estatus actualizado = 0: '. $fileid);
                                // grabar log en tabla nueva por crear
                                //
                                //
                            }
                            //mover a folder de finalizados
                            Storage::move($path_process.$filename, $path_store.$filename);
                            Log::info('archivo enviado al storage: '. $filename);
                            }
                        }
                    }
                }
            }//for
        } else { //buscar los que tiene algun error y procesar segun su purpose_code
            $status3 = DB::table('edidaimlers')->where('status', '3')->get();
            if (count($status3) == 0) {
                $status2 = DB::table('edidaimlers')->where('status', '2')->get();
                if (count($status2) == 0) { //No hay ninguno para procesar
                } else {
                    foreach ($status2 as $data) {
                        Log::info('Archivo en proceso: '.$data->shipment_id); //mostrar los procesados
                    }
                }
            } else {
                foreach ($status3 as $file) {
                    $fileid = $file->id;
                    $filename = $file->filename;
                    $shipment_id = $file->shipment_id;
                    if ($file->purpose_code == '05'){
                        $info05 = new edidaimler;
                        $info05->code05($fileid); //actualiza tabla 204
                    } elseif ($file->purpose_code == '01' ) {
                        $info01 = new edidaimler;
                        $info01->code01($fileid, $shipment_id, $filename);//Cancela tender tabla 204
                    }
                }
            }
        }
    dd('Finlaizado test');

*/

    
                
/*        

        $dir = 'Daimler/fromRyder/';
        $files = Storage::files($dir);
        $cantidad = count($files);

        for($i=0; $i<$cantidad; $i++){
            $filename = substr($files[$i], 18); //cortar nombre Daimler/fromRyder
            
            $archivo204 = DB::table('edidaimlers')->where('filename', $filename)->first();

            dd($archivo204);

            if ( substr($filename, 0, 6) == "RYD204") {

            $path = Storage::disk('local')->get($dir.$filename);//lectura local
            $array = explode("S5",$path);

            dd($array);

            } elseif ( substr($filename, 0, 6) == "RYD824") {
                
            }
        }
*/


    /* Para desmenuzar el archivo TXT*/
        // $longitud = count($array);
        // /* Separacion por ~ */
        // for($i=0; $i<$longitud; $i++)
        //     {
        //     echo "[".$i."] ".$array[$i];
        //     echo "<br>";
        //     }


        // $path = file::get(storage_path().'/app/public/fromRyder/'.$file);
        // $array = explode("S5",$path);
        // $count = count($array);

        //dd($array);



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //consulta tabla
        $name = 'ATIH_ELNEMRLOGP_204_4010_20151002190326052.txt';
        $datos = DB::table('imports')->where('filename', $name)->first();

        $datenow = date_create('now');
        $datetime = $datenow->format('YmdHisu');

        $email='sistemas01@autofleteshalcon.com';
        
        //dd($datos);
        $filename = 'ATIH_REQUEST_990_'.$datetime;
        //$st01 = $datos->st01;
        $st02 = $datos->st02;
        $b201 = $datos->b201;
        $b202 = $datos->b202;
        $b204 = $datos->b204;
        $b206 = $datos->b206;
        $b2a01 = $datos->b2a01;
        $l1101 = $datos->l1101;
        $l1102 = $datos->l1102;
        $l11_01 = $datos->l11_01;
        $l11_02 = $datos->l11_02;

        $file = fopen('storage/'.$filename.'.txt', "w");
        fwrite($file, "nuevo datos desde nuestra BD" . PHP_EOL);
        fwrite($file, "ST*990*".$st02."~"."B2*".$b201."*".$b202."*".$b204."*".$b206."~B2A*".$b2a01."~L11*".$l1101."*".$l1102."*ShipmentID~L11*".$l11_01."*".$l11_02."*Equipment Type~" . PHP_EOL);
        fclose($file);

        Mail::to($email)->send(new messagesend);

        Return ('Terminado!!');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $name= 'ATIH_ELNEMRLOGP_204_4010_20151002190326052.txt';
        $dir = 'app/public/'.$name;
        $path = file::get(storage_path($dir));
        $array = explode("~", $path);

        $row2 = $array[2];
        $tr2 = explode ("*", $row2);
            $row2td0= $tr2[0];//encabezado
            $row2td1= $tr2[1];
            $row2td2= $tr2[2];
        
        $row3 = $array[3];
        $tr3 = explode ("*", $row3);
            $row3td0= $tr3[0];//encabezado
            $row3td1= $tr3[1];
            $row3td2= $tr3[2];
            $row3td4= $tr3[4];
            $row3td6= $tr3[6];

        $row4 = $array[4];
        $tr4 = explode ("*", $row4);
            $row4td0= $tr4[0];//encabezado
            $row4td1= $tr4[1];
        
        $row6 = $array[6];
        $tr6 = explode ("*", $row6);
            $row6td0= $tr6[0];//encabezado
            $row6td1= $tr6[1];
            $row6td2= $tr6[2];

        $row7 = $array[7];
        $tr7 = explode ("*", $row7);
            $row7td0= $tr7[0];//encabezado
            $row7td1= $tr7[1];
            $row7td2= $tr7[2];

        $datos = new import();
        $datos->filename = $name;
        $datos->st01 = $row2td1;
        $datos->st02 = $row2td2;
        $datos->b201 = $row3td1;
        $datos->b202 = $row3td2;
        $datos->b204 = $row3td4;
        $datos->b206 = $row3td6;
        $datos->b2a01 = $row4td1;
        $datos->l1101 = $row6td1;
        $datos->l1102 = $row6td2;
        $datos->l11_01 = $row7td1;
        $datos->l11_02 = $row7td2;

        if ($datos->save()){
            return back()->with('msg', 'Insertados con exito');
        }
        else {
            return back()->with('err', 'Fallo al insertar');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\import  $import
     * @return \Illuminate\Http\Response
     */

    public function path()
    {
        $name= 'ATIH_ELNEMRLOGP_204_4010_20151002190326052.txt';
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
                <table class="table table-sm table-striped">
                <thead><tr><th>0 - '.$row0td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row0td5.'</td></tr>
                <tr><td>'.$row0td6.'</td></tr>
                <tr><td>'.$row0td7.'</td></tr>
                <tr><td>'.$row0td8.'</td></tr>
                <tr><td>'.$row0td11.'</td></tr>
                <tr><td>'.$row0td12.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row1 = $array[1];
        $tr1 = explode ("*", $row1);
            $row1td0= $tr1[0];
            $row1td2= $tr1[2];
            $row1td7= $tr1[7];
            $row1td8= $tr1[8];
                echo '
                <table class="table table-sm table-striped center">
                <thead><tr><th> 1 - '.$row1td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row1td2.'</td></tr>
                <tr><td>'.$row1td7.'</td></tr>
                <tr><td>'.$row1td8.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row3 = $array[3];
        $tr3 = explode ("*", $row3);
            $row3td0= $tr3[0];
            $row3td2= $tr3[2];
            $row3td4= $tr3[4];
            $row3td6= $tr3[6];
                echo '
                <table class="table table-sm table-striped center">
                <thead><tr><th>3 - '.$row3td0.'</th></tr></thead>
                <tbody class="text-center">                
                <tr><td>'.$row3td2.'</td></tr>
                <tr><td>'.$row3td4.'</td></tr>
                <tr><td>'.$row3td6.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row6 = $array[6];
        $tr6 = explode ("*", $row6);
            $row6td0= $tr6[0];
            $row6td1= $tr6[1];
            $row6td2= $tr6[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th> 6 - '.$row6td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row6td1.'</td></tr>
                <tr><td>'.$row6td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row7 = $array[7];
        $tr7 = explode ("*", $row7);
            $row7td0= $tr7[0];
            $row7td1= $tr7[1];
            $row7td2= $tr7[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>7 - '.$row7td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row7td1.'</td></tr>
                <tr><td>'.$row7td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row15 = $array[15];
        $tr15 = explode ("*", $row15);
            $row15td0= $tr15[0];
            $row15td2= $tr15[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>15 - '.$row15td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row15td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row18 = $array[18];
        $tr18 = explode ("*", $row18);
            $row18td0= $tr18[0];
            $row18td2= $tr18[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>18 - '.$row18td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row18td2.'</td></tr>
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
            $row19td7= $tr19[7];
            $row19td8= $tr19[8];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>19 - '.$row19td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row19td1.'</td></tr>
                <tr><td>'.$row18td2.'</td></tr>
                <tr><td>'.$row19td3.'</td></tr>
                <tr><td>'.$row19td4.'</td></tr>
                <tr><td>'.$row19td5.'</td></tr>
                <tr><td>'.$row19td6.'</td></tr>
                <tr><td>'.$row19td7.'</td></tr>
                <tr><td>'.$row19td8.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row20 = $array[20];
        $tr20 = explode ("*", $row20);
            $row20td0= $tr20[0];
            $row20td1= $tr20[1];
            $row20td2= $tr20[2];
            $row20td3= $tr20[3];
            $row20td4= $tr20[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>20 - '.$row20td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row20td1.'</td></tr>
                <tr><td>'.$row20td2.'</td></tr>
                <tr><td>'.$row20td3.'</td></tr>
                <tr><td>'.$row20td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row21 = $array[21];
        $tr21 = explode ("*", $row21);
            $row21td0= $tr21[0];
            $row21td1= $tr21[1];
            $row21td2= $tr21[2];
            $row21td3= $tr21[3];
            $row21td4= $tr21[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>21 - '.$row21td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row21td1.'</td></tr>
                <tr><td>'.$row21td2.'</td></tr>
                <tr><td>'.$row21td3.'</td></tr>
                <tr><td>'.$row21td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row22 = $array[22];
        $tr22 = explode ("*", $row22);
            $row22td0= $tr22[0];
            $row22td2= $tr22[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>22 - '.$row22td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row22td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row23 = $array[23];
        $tr23 = explode ("*", $row23);
            $row23td0= $tr23[0];
            $row23td1= $tr23[1];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>23 - '.$row23td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row23td1.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row24 = $array[24];
        $tr24 = explode ("*", $row24);
            $row24td0= $tr24[0];
            $row24td1= $tr24[1];
            $row24td2= $tr24[2];
            $row24td3= $tr24[3];
            $row24td4= $tr24[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>24 - '.$row24td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row24td1.'</td></tr>
                <tr><td>'.$row24td2.'</td></tr>
                <tr><td>'.$row24td3.'</td></tr>
                <tr><td>'.$row24td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row25 = $array[25];
        $tr25 = explode ("*", $row25);
            $row25td0= $tr25[0];
            $row25td4= $tr25[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>25 - '.$row25td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row25td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row26 = $array[26];
        $tr26 = explode ("*", $row26);
            $row26td0= $tr26[0];
            $row26td1= $tr26[1];
            $row26td2= $tr26[2];
            $row26td3= $tr26[3];
            $row26td4= $tr26[4];
            $row26td5= $tr26[5];
            $row26td6= $tr26[6];
            $row26td7= $tr26[7];
            $row26td8= $tr26[8];
            $row26td9= $tr26[9];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>26 - '.$row26td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row26td1.'</td></tr>
                <tr><td>'.$row26td2.'</td></tr>
                <tr><td>'.$row26td3.'</td></tr>
                <tr><td>'.$row26td4.'</td></tr>
                <tr><td>'.$row26td5.'</td></tr>
                <tr><td>'.$row26td6.'</td></tr>
                <tr><td>'.$row26td7.'</td></tr>
                <tr><td>'.$row26td8.'</td></tr>
                <tr><td>'.$row26td9.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row30 = $array[30];
        $tr30 = explode ("*", $row30);
            $row30td0= $tr30[0];
            $row30td1= $tr30[1];
            $row30td2= $tr30[2];
            $row30td3= $tr30[3];
            $row30td4= $tr30[4];
            $row30td5= $tr30[5];
            $row30td6= $tr30[6];
            $row30td7= $tr30[7];
            $row30td8= $tr30[8];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>30 - '.$row30td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row30td1.'</td></tr>
                <tr><td>'.$row30td2.'</td></tr>
                <tr><td>'.$row30td3.'</td></tr>
                <tr><td>'.$row30td4.'</td></tr>
                <tr><td>'.$row30td5.'</td></tr>
                <tr><td>'.$row30td6.'</td></tr>
                <tr><td>'.$row30td7.'</td></tr>
                <tr><td>'.$row30td8.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row31 = $array[31];
        $tr31 = explode ("*", $row31);
            $row31td0= $tr31[0];
            $row31td1= $tr31[1];
            $row31td2= $tr31[2];
            $row31td3= $tr31[3];
            $row31td4= $tr31[4];
            $row31td5= $tr31[5];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>31 - '.$row31td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row31td1.'</td></tr>
                <tr><td>'.$row31td2.'</td></tr>
                <tr><td>'.$row31td3.'</td></tr>
                <tr><td>'.$row31td4.'</td></tr>
                <tr><td>'.$row31td5.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row32 = $array[32];
        $tr32 = explode ("*", $row32);
            $row32td0= $tr32[0];
            $row32td2= $tr32[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>32 - '.$row32td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row32td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row33 = $array[33];
        $tr33 = explode ("*", $row33);
            $row33td0= $tr33[0];
            $row33td1= $tr33[1];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>33 - '.$row33td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row33td1.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row34 = $array[34];
        $tr34 = explode ("*", $row34);
            $row34td0= $tr34[0];
            $row34td1= $tr34[1];
            $row34td2= $tr34[2];
            $row34td3= $tr34[3];
            $row34td4= $tr34[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>34 - '.$row34td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row34td1.'</td></tr>
                <tr><td>'.$row34td2.'</td></tr>
                <tr><td>'.$row34td3.'</td></tr>
                <tr><td>'.$row34td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row36 = $array[36];
        $tr36 = explode ("*", $row36);
            $row36td0= $tr36[0];
            $row36td4= $tr36[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>36 - '.$row36td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row36td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";
        
        $row37 = $array[37];
        $tr37 = explode ("*", $row37);
            $row37td0= $tr37[0];
            $row37td1= $tr37[1];
            $row37td2= $tr37[2];
            
            $row37td4= $tr37[4];
            $row37td5= $tr37[5];
            $row37td6= $tr37[6];
            $row37td7= $tr37[7];
            $row37td8= $tr37[8];
            $row37td9= $tr37[9];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>37 - '.$row37td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row37td1.'</td></tr>
                <tr><td>'.$row37td2.'</td></tr>
                
                <tr><td>'.$row37td4.'</td></tr>
                <tr><td>'.$row37td5.'</td></tr>
                <tr><td>'.$row37td6.'</td></tr>
                <tr><td>'.$row37td7.'</td></tr>
                <tr><td>'.$row37td8.'</td></tr>
                <tr><td>'.$row37td9.'</td></tr>
                </tbody></table>';
                echo "<hr>";

echo '</div><div class="col-sm-5"></div></div>';

//Envio de email




    return \view('vista');

    }

    public function show(Request $request)
    {
        //carga del archivo al array
        $contenido = file_get_contents($request->file);
        //separacion de array
        $array = explode("~", $contenido);


    /* Para desmenuzar el archivo TXT*/
        $longitud = count($array);
        /* Separacion por ~ */
        for($i=0; $i<$longitud; $i++)
            {
            echo "[".$i."] ".$array[$i];
            echo "<br>";
            }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\import  $import
     * @return \Illuminate\Http\Response
     */
    public function edit(import $import)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\import  $import
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, import $import)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\import  $import
     * @return \Illuminate\Http\Response
     */
    public function destroy(import $import)
    {
        //
    }
        /////////  COPIA DEL EDI214DAIMLER -> COMANDS

    public function handle()
    {
        $today = date_create('now');
        $files = Storage::disk('sftp')->files(''); //muestra los archivos en array
        $cantidad = count($files); //contador de archivos en el directorio
        for($i=0; $i<$cantidad; $i++)
            {
            //validar Solo archivos TxT
            if ( substr($files[$i],-4)==".txt") {
            //validar archivos con nombre incial RYD203
            if (substr($files[$i], 0, 6) == "RYD204") {
                //Validar si ya existe el archivo
                $buscar = DB::table('edidaimlers')->where('filename', $files[$i])->first();
            
            if (empty($buscar)) {
                Log::info('Archivo:'.$files[$i]);
                //guardar el nombre del archivo


            
            $file = Storage::disk('sftp')->get($files[$i]); //lectura del archivo txt
            $array = explode("~", $file); //separacion por signo ~            
                Log::info('Archivo separado en array ~');
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
            $row2 = $array[2];
            $tr2 = explode ("*", $row2);
                $row2td2= $tr2[2];                
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
                $row20td2= $tr20[2];
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
            //almacenar en mysql
            DB::table('edidaimlers')->insert(['filename' => $files[$i], 'shipment_id' => $row3td4,'created_at' => $today->format('Y-m-d H:i:s'),'updated_at' => $today->format('Y-m-d H:i:s')]);
            Log::info('Archivo Almancenado con exito!');
            //enviar datos sqlsrv
            DB::connection('sqlsrv')->table("edi_daimler")->insert([
                'id_qualifier_sender' => $row0td5,
                'id_sender' => $row0td6,
                'id_qualifier_receiver' => $row0td7,
                'id_receiver' => $row0td8,
                'version_number' => $row0td11,
                'control_number' => $row0td12,
                'sender_code' => $row1td2,
                'agency_code' => $row1td7,
                'industry_identifier' => $row1td8,
                'control_number_sender' => $row2td2,
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
                'id_tracking_number' => $row20td2,
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
                Log::info('Datos almacenados en SqlSrv!!!');

                $id = $row3td4;
                $origen = $row15td2;
                $destino = $row24td2;
                $fecha = date('d / M / Y', strtotime($row13td2));
                $email = Storage::disk('public')->get('to_mail.txt');//archivo con el correo
                Mail::to($email)->send(new NotificaDaimler($id, $origen, $destino, $fecha));
                Log::info('Correo enviado!!');

                //inicia confirmacion de recibido 997
                $data997 = \DB::connection('sqlsrv')->table("edi_daimler_997_send")->where('control_number_sender', '=', $row2td2)->first();
                
                $id = $data997->id_incremental;
                $i = strlen($id);
                if ($i == 1) { //convertir en 9 digitos
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
                $filename = trim($data997->id_receiver).'_'.$data997->sender_code.'_997_'.date('Ymd', strtotime($data997->date_time)).'_'.$idnew;
                //Crear archivo TxT 997
                $file997 = Storage::disk('sftp')->put('files997/'.$filename.'.txt', "ISA*00*          *00*          *".$data997->id_qualifier_receiver."*".$data997->id_receiver."*".$data997->id_qualifier_sender."*".$data997->id_sender."*".date('ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$data997->version_number."*".$data997->control_number."*".$idnew."*0*T*^~GS*FA*".trim($data997->id_receiver)."*".$data997->sender_code."*".date('Ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*0001*".$data997->agency_code."*".$data997->industry_identifier."~ST*997*0001~AK1*SM*".$data997->control_number_sender."~AK9*".$data997->code."*".$id."*".$id."*".$id."~SE*4*0001~GE*1*".$id."~IEA*1*".$idnew."~");

                    if (empty($file997)) {
                        Log::error('Hubo fallos al crear archivo 997');
                    } else {
                        Log::info('Archivo 997 creado');
                        // cambiar valor a 0 para no volverlo a leer
                        $up = DB::connection('sqlsrv')->table("edi_daimler_997_send")->where([ ['id_incremental', '=', $id] ])->update(['send_txt' => '0']);
                        Log::info('tabla edi_daimler_997_send actualizada');
                    }                    
                //fin de confirmacion
 
            }
        } //RYD204
            
            else {
                Log::info('No se encontraron nuevos archivos');
            }
        }
    }
    }



}
