<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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

    public function Notificacion($code, $shipment_id, $destino, $origen, $fecha, $hora) {
        //Notificar por correo
        $id = $shipment_id;
        $email = env('MAIL_SEND_DAIMLER');
        $ccmails = env('CCMAIL_SEND_DAIMLER');
        Mail::to($email)->cc($ccmails)->send(new NotificaDaimler($code, $id, $origen, $destino, $fecha, $hora));
            // informar cual fue la notificacion
            if ($code == 5) {
                Log::info('Correo de Actualizacion enviado!!');
            } elseif($code == 1) {
                Log::info('Correo de Cancelacion enviado!!');
            } else {
                Log::info('Correo nuevo tender enviado!');
            }
    }

    public function create997($shipment_id, $fileid, $filename) {
        // Directorios de los txt
        $path_process = 'Daimler/fromRyder_process/';
        $path_store = 'Daimler/fromRyder_arch/';
        $path_997 = 'Daimler/toRyder997/';
        
        //inicia confirmacion de recibido 997
        $data997 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_997_send")->where('shipment_identification_number', '=', $shipment_id)->first();
        if (empty($data997)) { Log::critical('No existen datos edi_daimler_997_send'); }
        else {
            $id = $data997->id_incremental;
            $i = strlen($id);//convertir en 9 digitos
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
                $file997 = trim($data997->id_receiver).'_'.$data997->sender_code.'_997_'.date('Ymd', strtotime($data997->date_time)).'_'.$idnew;
                $datafile = "ISA*00*          *00*          *".$data997->id_qualifier_receiver."*".$data997->id_receiver."*".$data997->id_qualifier_sender."*".$data997->id_sender."*".date('ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$data997->version_number."*".$data997->control_number."*".$data997->control_number_sender."*0*P*^~GS*FA*".trim($data997->id_receiver)."*".$data997->sender_code."*".date('Ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$idnew."*".$data997->agency_code."*".$data997->industry_identifier."~ST*997*".$idnew."~AK1*SM*".$data997->control_number_sender."~AK9*".$data997->code."*".$idnew."*".$idnew."*".$idnew."~SE*4*".$idnew."~GE*1*".$idnew."~IEA*1*".$idnew."~";
                //Crear archivo TxT 997
                $file997local = Storage::disk('local')->put($path_997.$file997.'.txt', $datafile);
                $file997ftp = Storage::disk('ftp')->put('toRyder/'.$file997.'.txt', $datafile);
            if (empty($file997local)) {
                Log::error('fallos al crear archivo 997 Local');
            } elseif (empty($file997ftp)){
                Log::error('fallos al crear archivo 997 Ftp');
            } else {
                Log::info('Archivo 997 creado');
                // cambiar valor a 0 para no volverlo a leer
                $up997 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_997_send")->where([ ['id_incremental', '=', $data997->id_incremental] ])->update(['send_txt' => '0']);
                if (empty($up997)) { Log::warning('Hubo fallos al actualizar edi_daimler_997_send');
                } else {
                    Log::info('tabla edi_daimler_997_send actualizada');
                    // actualizar status archivo mysql
                    $update204 = EdiDaimler::findOrFail($fileid);
                    $update204->status = '0';
                    if ($update204->save()) {
                        Log::info('Estatus actualizado = 0: '. $fileid);
                        // grabar log en tabla nueva por crear
                        //
                    }
                    //mover a folder de finalizados
                    Storage::move($path_process.$filename, $path_store.$filename);
                    Log::info('archivo enviado al storage: '. $filename);
                }
            }
        }
    }

    public function update990($shipment_id) {
        //buscamos en tabla 990 si existe actualizar(si respodieron)
        $data990 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_990")->where('shipment_identification_number', '=', $shipment_id)->first();
        if (empty($data990)) { }//si es null no hacer nada
        else { //si existe actualizar el purpose_code a 01
            $update990 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_990")->where([ ['shipment_identification_number', '=', $shipment_id] ])->update(['purpose_code' => '01']);
            //validar update990
            if (empty($update990)) { Log::warning('Fallo actualizacion 01 edi_daimler_990 ');}
            else { Log::info('Se actualizo edi_daimler_990 01 con exito!'); }
        }
    }
}
