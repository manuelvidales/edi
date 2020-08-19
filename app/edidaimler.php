<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NotificaDaimler;



class edidaimler extends Model
{
    public function code05($id) {
        $path_process = 'Daimler/fromRyder_process/';
        $datafile = edidaimler::where('id', $id)->first();
            $fileid = $datafile->id;
            $filename = $datafile->filename;
            $shipment_id = $datafile->shipment_id;
        // validacion si existe el tender en Sqlsrv
        $valida = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where('shipment_identification_number', '=', $shipment_id)->first();
        if (empty($valida)) { Log::warning('No existen datos edi_daimler_204 con id: '.$shipment_id); }
        else {
            $read_file = Storage::disk('local')->get($path_process.$filename);//lectura local del archivo 204
            $array = explode("S5",$read_file);
            Log::info('Archivo separado en array S5');
            $count = count($array);
            for($i = 0; $i<$count; $i++){
                switch(substr($array[$i],0,3))
                {
                    case "*1*":
                        $S5_uno = explode("~",$array[$i]); // S5 inicial
                        //Campos selecionados
                        $G62_1 = explode("*",$S5_uno[3]);
                        $N1_1 = explode("*",$S5_uno[5]);
                        //G62
                            $stop1_date=$G62_1[2];
                            $stop1_time=$G62_1[4];
                            $stop1_time_code=$G62_1[5];
                        //N1
                            $stop1=$N1_1[2];
                    break;
                    case "*2*":
                        $S5_dos = explode("~",$array[$i]);
                        //Campos selecionados
                        $G62_2 = explode("*",$S5_dos[3]);
                        $N1_2 = explode("*",$S5_dos[5]);
                        //G62
                            $stop2_date=$G62_2[2];
                            $stop2_time=$G62_2[4];
                            $stop2_time_code=$G62_2[5];
                        //N1
                            $stop2=$N1_2[2];
                    break;
                    case "*3*":
                        $S5_tres = explode("~",$array[$i]);
                        //Campos selecionados
                        $G62_3 = explode("*",$S5_tres[3]);
                        $N1_3 = explode("*",$S5_tres[5]);
                        //G62
                            $stop3_date=$G62_3[2];
                            $stop3_time=$G62_3[4];
                            $stop3_time_code=$G62_3[5];
                        //N1
                            $stop3=$N1_3[2];
                    break;
                    case "*4*":
                        $S5_cuatro = explode("~",$array[$i]);
                        //Campos selecionados
                        $G62_4 = explode("*",$S5_cuatro[3]);
                        $N1_4 = explode("*",$S5_cuatro[5]);
                        //G62
                            $stop4_date=$G62_4[2];
                            $stop4_time=$G62_4[4];
                            $stop4_time_code=$G62_4[5];
                        //N1
                            $stop4=$N1_4[2];
                    break;
                    case "*5*":
                        $S5_cinco = explode("~",$array[$i]);
                        //Campos selecionados
                        $G62_5 = explode("*",$S5_cinco[3]);
                        $N1_5 = explode("*",$S5_cinco[5]);
                        //G62
                            $stop5_date=$G62_5[2];
                            $stop5_time=$G62_5[4];
                            $stop5_time_code=$G62_5[5];
                        //N1
                            $stop5=$N1_5[2];
                    break;
                    case "*6*":
                        $S5_seis = explode("~",$array[$i]);
                        //Campos selecionados
                        $G62_6 = explode("*",$S5_seis[3]);
                        $N1_6 = explode("*",$S5_seis[5]);
                        //G62
                            $stop6_date=$G62_6[2];
                            $stop6_time=$G62_6[4];
                            $stop6_time_code=$G62_6[5];
                        //N1
                            $stop6=$N1_6[2];
                    break;
                    case "*7*":
                        $S5_siete = explode("~",$array[$i]);
                        //Campos selecionados
                        $G62_7 = explode("*",$S5_siete[3]);
                        $N1_7 = explode("*",$S5_siete[5]);
                        //G62
                            $stop7_date=$G62_7[2];
                            $stop7_time=$G62_7[4];
                            $stop7_time_code=$G62_7[5];
                        //N1
                            $stop7=$N1_7[2];
                    break;
                    case "*8*":
                        $S5_ocho = explode("~",$array[$i]);
                        //Campos selecionados
                        $G62_8 = explode("*",$S5_ocho[3]);
                        $N1_8 = explode("*",$S5_ocho[5]);
                        //G62
                            $stop8_date=$G62_8[2];
                            $stop8_time=$G62_8[4];
                            $stop8_time_code=$G62_8[5];
                        //N1
                            $stop8=$N1_8[2];
                    break;
                }
            }

        //Update status en mysql
            $data204 = EdiDaimler::findOrFail($fileid);
            $data204->status = '2';
            if ($data204->save()) {
                Log::info('Archivo actualizado Mysql');
                //actualizar fechas SqlSrv tabla edi_daimler_204 txt204
                if ($count == 3) {
                    $destino = $stop2;
                    $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_id] ])->update([
                        'purpose_code' => '05',
                        'stop1_date' => $stop1_date,
                        'stop1_time' => $stop1_time,
                        'stop1_time_code' => $stop1_time_code,
                        'stop2_date' => $stop2_date,
                        'stop2_time' => $stop2_time,
                        'stop2_time_code' => $stop2_time_code]);
                } elseif($count == 4) {
                    $destino = $stop3;
                    $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_id] ])->update([
                        'purpose_code' => '05',
                        'stop1_date' => $stop1_date,
                        'stop1_time' => $stop1_time,
                        'stop1_time_code' => $stop1_time_code,
                        'stop2_date' => $stop2_date,
                        'stop2_time' => $stop2_time,
                        'stop2_time_code' => $stop2_time_code,
                        'stop3_date' => $stop3_date,
                        'stop3_time' => $stop3_time,
                        'stop3_time_code' => $stop3_time_code]);                        
                } elseif($count == 5) {
                    $destino = $stop4;
                    $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_id] ])->update([
                        'purpose_code' => '05',
                        'stop1_date' => $stop1_date,
                        'stop1_time' => $stop1_time,
                        'stop1_time_code' => $stop1_time_code,
                        'stop2_date' => $stop2_date,
                        'stop2_time' => $stop2_time,
                        'stop2_time_code' => $stop2_time_code,
                        'stop3_date' => $stop3_date,
                        'stop3_time' => $stop3_time,
                        'stop3_time_code' => $stop3_time_code,
                        'stop4_date' => $stop4_date,
                        'stop4_time' => $stop4_time,
                        'stop4_time_code' => $stop4_time_code]);
                } elseif($count == 6) {
                    $destino = $stop5;
                    $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_id] ])->update([
                        'purpose_code' => '05',
                        'stop1_date' => $stop1_date,
                        'stop1_time' => $stop1_time,
                        'stop1_time_code' => $stop1_time_code,
                        'stop2_date' => $stop2_date,
                        'stop2_time' => $stop2_time,
                        'stop2_time_code' => $stop2_time_code,
                        'stop3_date' => $stop3_date,
                        'stop3_time' => $stop3_time,
                        'stop3_time_code' => $stop3_time_code,
                        'stop4_date' => $stop4_date,
                        'stop4_time' => $stop4_time,
                        'stop4_time_code' => $stop4_time_code,
                        'stop5_date' => $stop5_date,
                        'stop5_time' => $stop5_time,
                        'stop5_time_code' => $stop5_time_code]);
                } elseif($count == 7) {
                    $destino = $stop6;
                    $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_id] ])->update([
                        'purpose_code' => '05',
                        'stop1_date' => $stop1_date,
                        'stop1_time' => $stop1_time,
                        'stop1_time_code' => $stop1_time_code,
                        'stop2_date' => $stop2_date,
                        'stop2_time' => $stop2_time,
                        'stop2_time_code' => $stop2_time_code,
                        'stop3_date' => $stop3_date,
                        'stop3_time' => $stop3_time,
                        'stop3_time_code' => $stop3_time_code,
                        'stop4_date' => $stop4_date,
                        'stop4_time' => $stop4_time,
                        'stop4_time_code' => $stop4_time_code,
                        'stop5_date' => $stop5_date,
                        'stop5_time' => $stop5_time,
                        'stop5_time_code' => $stop5_time_code,
                        'stop6_date' => $stop6_date,
                        'stop6_time' => $stop6_time,
                        'stop6_time_code' => $stop6_time_code]);
                } elseif($count == 8) {
                    $destino = $stop7;
                    $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_id] ])->update([
                        'purpose_code' => '05',
                        'stop1_date' => $stop1_date,
                        'stop1_time' => $stop1_time,
                        'stop1_time_code' => $stop1_time_code,
                        'stop2_date' => $stop2_date,
                        'stop2_time' => $stop2_time,
                        'stop2_time_code' => $stop2_time_code,
                        'stop3_date' => $stop3_date,
                        'stop3_time' => $stop3_time,
                        'stop3_time_code' => $stop3_time_code,
                        'stop4_date' => $stop4_date,
                        'stop4_time' => $stop4_time,
                        'stop4_time_code' => $stop4_time_code,
                        'stop5_date' => $stop5_date,
                        'stop5_time' => $stop5_time,
                        'stop5_time_code' => $stop5_time_code,
                        'stop6_date' => $stop6_date,
                        'stop6_time' => $stop6_time,
                        'stop6_time_code' => $stop6_time_code,
                        'stop7_date' => $stop7_date,
                        'stop7_time' => $stop7_time,
                        'stop7_time_code' => $stop7_time_code]);
                } elseif($count == 9) {
                    $destino = $stop8;
                    $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_id] ])->update([
                        'purpose_code' => '05',
                        'stop1_date' => $stop1_date,
                        'stop1_time' => $stop1_time,
                        'stop1_time_code' => $stop1_time_code,
                        'stop2_date' => $stop2_date,
                        'stop2_time' => $stop2_time,
                        'stop2_time_code' => $stop2_time_code,
                        'stop3_date' => $stop3_date,
                        'stop3_time' => $stop3_time,
                        'stop3_time_code' => $stop3_time_code,
                        'stop4_date' => $stop4_date,
                        'stop4_time' => $stop4_time,
                        'stop4_time_code' => $stop4_time_code,
                        'stop5_date' => $stop5_date,
                        'stop5_time' => $stop5_time,
                        'stop5_time_code' => $stop5_time_code,
                        'stop6_date' => $stop6_date,
                        'stop6_time' => $stop6_time,
                        'stop6_time_code' => $stop6_time_code,
                        'stop7_date' => $stop7_date,
                        'stop7_time' => $stop7_time,
                        'stop7_time_code' => $stop7_time_code,
                        'stop8_date' => $stop8_date,
                        'stop8_time' => $stop8_time,
                        'stop8_time_code' => $stop8_time_code]);
                }
                else {
                    Log::error('No hubo coincidencias en Array Count(3,4,5,6,7,8)');
                }
                    if (empty($update05)) {
                        Log::critical('Fallo actualizar Sqlsrv purpose:05 tabla edi_daimler_204');
                    } else {
                        Log::info('Info actualizada Sqlsrv purpose:05');

                        $code = '5'; //para plantilla correo con markdown
                        $origen = $stop1;
                        $fecha = date('d/M/Y', strtotime($stop1_date));
                        $hora = date('H:i', strtotime($stop1_time));

                        //para envio de notificacion
                        $code05 = new edidaimler();
                        $code05->Notificacion($code, $shipment_id, $origen, $destino, $fecha, $hora);

                        // para confirmacion con el txt 997
                        $txt997 = new edidaimler();
                        $txt997->create997($shipment_id, $fileid, $filename);
                    }
            } else { Log::error('Al actualizar status 2 del purpose_code 05'); }
        }
    }

    public function code01($fileid, $shipment_id, $filename) {
        $valida01 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where('shipment_identification_number', '=', $shipment_id)->first();
        if (empty($valida01)) {
            Log::warning('No existen datos edi_daimler_204 con id: '.$shipment_id);
        } else {
                //almacenar en mysql
                $data204 = EdiDaimler::findOrFail($fileid);
                $data204->status = '2';
                if ($data204->save()) {
                Log::info('Archivo actualizado Mysql');
                $update01 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_id] ])->update(['purpose_code' => '01']);
                if (empty($update01)) { 
                    Log::critical('Fallo actualizar Sqlsrv purpose:01 tabla edi_daimler_204');
                } else {
                    Log::info('pedido actualizado purpose:01');

                    $code = '1'; //para plantilla correo con markdown
                    $origen = $valida01->stop1;
                    $destino = $valida01->stop2;
                    $fecha = date('d/M/Y', strtotime($valida01->stop1_date));
                    $hora = date('H:i', strtotime($valida01->stop1_time));

                    //para envio de notificacion
                    $code05 = new edidaimler();
                    $code05->Notificacion($code, $shipment_id, $origen, $destino, $fecha, $hora);

                    // para confirmacion con el txt 997
                    $txt997 = new edidaimler();
                    $txt997->create997($shipment_id, $fileid, $filename);

                    // update tabla 990 si existe el tender
                    $update990 = new edidaimler();
                    $update990->update990($shipment_id);
                }
            } //mysql
        }
    }
}
