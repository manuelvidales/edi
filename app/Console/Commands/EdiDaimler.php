<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\Mail\NotificaDaimler;


class EdiDaimler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edi:daimler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'received code 204 client Daimler';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = date_create('now');
        //credenciales del FTP desde el env
        $ftp_server = env('FTP_HOST');
        $ftp_user = env('FTP_USERNAME');
        $ftp_pass = env('FTP_PASSWORD');

        // establecer una conexión o finalizarla
        $conn_id = ftp_connect($ftp_server) or die("No se pudo conectar a $ftp_server");
        $login = ftp_login($conn_id, $ftp_user, $ftp_pass);
        //validar conexion FTP
        if ( @$login ) {
        // Obtener los archivos del directorio ftp
            $files = ftp_nlist($conn_id, 'fromRyder');
            $cantidad = count($files);
        for($i=0; $i<$cantidad; $i++){
            $filename = $files[$i];
//validar formato archivos .txt y con codigo #204
        if ( substr($filename,-4)==".txt" and substr($filename, 0, 16) == "fromRyder/RYD204") {
                //Validar si ya existe el archivo
                $buscar = DB::table('edidaimlers')->where('filename', $filename)->first();
            if (empty($buscar)) {
                Log::info('Archivo:'.$filename);
                // Se procede a descargar archivo
                $local = 'public/storage/'.$filename; //ruta para almacenar
                    if (ftp_get($conn_id, $local, $filename, FTP_BINARY)) { //descarga
                        Log::info('Descarga archivo exitoso');
                            //elimina el archivo del directorio ftp
                            if (ftp_delete($conn_id, $filename)) {
                                Log::info($filename .' se elimino satisfactoriamente');                
                            } else {
                                Log::warning('No se pudo eliminar ');
                            }
                    } else { //esta parte mover al final
                        Log::error('Ha ocurrido un problema al descargar');
                    }
                $path = file::get('public/storage/'.$filename);//lectura local
                $array = explode("~", $path); //array inicial
                    Log::info('Archivo separado en array ~');
                $finalcount = count($array);
                for($i = 0; $i<$finalcount; $i++) {
                    $data_item=explode("*",$array[$i]); // explode the segment into an array of data_items
                    switch($i) { //Encabezado 204
                        case 0: //ISA
                            $ISA05_id_qualifier_sender=$data_item[5];
                            $ISA06_id_sender=$data_item[6];
                            $ISA07_id_qualifier_receiver=$data_item[7];
                            $ISA08_id_receiver=$data_item[8];
                            $ISA11_version_number=$data_item[11];
                            $ISA12_control_number=$data_item[12];
                        break;
                        case 1://GS
                            $GS02_sender_code=$data_item[2];
                            $GS07_agency_code=$data_item[7];
                            $GS08_industry_identifier=$data_item[8];
                        break;
                        case 2://ST
                            $ST02_control_number_sender=$data_item[2];
                        break;
                        case 3://B2
                            $B202_alpha_code=$data_item[2];
                            $B204_shipment_identification_number=$data_item[4];
                            $B206_method_payment=$data_item[6];
                        break;
                        case 4://B2A
                            $B2A01_purpose_code = $data_item[1];
                        break;
                        case 7://L11
                            $L1103_reference_identification=$data_item[1];
                            $L1103_reference_identification_qualifier=$data_item[2];
                        break;
                    //s5 inicial
                        case 9://S5
                            $S501_stop_number_load=$data_item[1];
                            $S502_stop_reason_code_load=$data_item[2];
                            $S503_weight_load=$data_item[3];
                            $S504_weight_units_load=$data_item[4];
                            $S505_quantity_load=$data_item[5];
                            $S506_unit_for_measurement_load=$data_item[6];
                        break;
                        case 12://G62
                            $G6202_load_date_1=$data_item[2];
                            $G6204_load_time_1=$data_item[4];
                            $G6205_load_time_code_1=$data_item[5];
                        break;
                        case 13://G62
                            $G6201_load_date_qualifier_2=$data_item[1];
                            $G6202_load_date_2=$data_item[2];
                            $G6203_load_time_qualifier_2=$data_item[3];
                            $G6204load_time_2=$data_item[4];
                            $G6205_load_time_code_2=$data_item[5];
                        break;
                        case 14://N1
                            $N102_origin=$data_item[2];
                        break;
                        case 15://N3
                            $N301_addres_origin=$data_item[1];
                        break;
                        case 16://N4
                            $N401_city_origin=$data_item[1];
                            $N402_state_origin=$data_item[2];
                            $N403_postal_code_origin=$data_item[3];
                            $N404_country_origin=$data_item[4];
                        break;
                    }
                }
        //Condicionales por lineas totales
                if ($finalcount == 32) {
                    for($i = 0; $i<$finalcount; $i++) {
                        $data_item=explode("*",$array[$i]);
                        switch($i) {   //s5 Final
                            case 18://S5
                                $S501_stop_number_stop1=$data_item[1];
                                $S502_stop_reason_code_stop1=$data_item[2];
                                $S503_weight_stop1=$data_item[3];
                                $S504_weight_units_stop1=$data_item[4];
                                $S505_quantity_stop1=$data_item[5];
                                $S506_unit_for_measurement_stop1=$data_item[6];
                            break;
                            case 19://L11
                                $L1101_tracking_number=$data_item[1];
                                $l1102_id_tracking_number=$data_item[2];
                            break;
                            case 21://G62
                                $G6202_stop1_date=$data_item[2];
                                $G6204_stop1_time=$data_item[4];
                                $G6205_stop1_time_code=$data_item[5];
                            break;
                            case 23://N1
                                $N102_stop1=$data_item[2];
                            break;
                            case 24://N3
                                $N301_addres_stop1=$data_item[1];
                            break;
                            case 25://N4
                                $N401_city_stop1=$data_item[1];
                                $N402_state_stop1=$data_item[2];
                                $N403_postal_code_stop1=$data_item[3];
                                $N404_country_stop1=$data_item[4];
                            break;
                        }
                    }
                } elseif($finalcount == 48) {
                    for($i = 0; $i<$finalcount; $i++) {
                        $data_item=explode("*",$array[$i]);
                        switch($i) {   //s5 Final
                            case 34://S5
                                $S501_stop_number_stop1=$data_item[1];
                                $S502_stop_reason_code_stop1=$data_item[2];
                                $S503_weight_stop1=$data_item[3];
                                $S504_weight_units_stop1=$data_item[4];
                                $S505_quantity_stop1=$data_item[5];
                                $S506_unit_for_measurement_stop1=$data_item[6];
                            break;
                            case 35://L11
                                $L1101_tracking_number=$data_item[1];
                                $l1102_id_tracking_number=$data_item[2];
                            break;
                            case 37://G62
                                $G6202_stop1_date=$data_item[2];
                                $G6204_stop1_time=$data_item[4];
                                $G6205_stop1_time_code=$data_item[5];
                            break;
                            case 39://N1
                                $N102_stop1=$data_item[2];
                            break;
                            case 40://N3
                                $N301_addres_stop1=$data_item[1];
                            break;
                            case 41://N4
                                $N401_city_stop1=$data_item[1];
                                $N402_state_stop1=$data_item[2];
                                $N403_postal_code_stop1=$data_item[3];
                                $N404_country_stop1=$data_item[4];
                            break;
                        }
                    }
                }
                else {
                    Log::error('No hubo coincidencias en Array(32 o 48)');
                }
        // Validar si existe el shipment_id para procesar el purpose_code
            $shipm = DB::table('edidaimlers')->where('shipment_id', $B204_shipment_identification_number)->first();
            if (empty($shipm)) { //si es null se procesa por primera vez Codigo
        //almacenar en mysql
            $savefile = DB::table('edidaimlers')->insert(['filename' => $filename, 'shipment_id' => $B204_shipment_identification_number,'created_at' => $today->format('Y-m-d H:i:s'),'updated_at' => $today->format('Y-m-d H:i:s')]);
                    if (empty($savefile)) { Log::warning('Nombre de archivo no se almaceno Mysql'); }
                    else { Log::info('Archivo Almacenado Mysql'); }
        //almacenar en SqlSrv
            $save204 = DB::connection('sqlsrv')->table("edi_daimler")->insert([
                    'id_qualifier_sender' => $ISA05_id_qualifier_sender,
                    'id_sender' => $ISA06_id_sender,
                    'id_qualifier_receiver' => $ISA07_id_qualifier_receiver,
                    'id_receiver' => $ISA08_id_receiver,
                    'version_number' => $ISA11_version_number,
                    'control_number' => $ISA12_control_number,
                    'sender_code' => $GS02_sender_code,
                    'agency_code' => $GS07_agency_code,
                    'industry_identifier' => $GS08_industry_identifier,
                    'control_number_sender' => $ST02_control_number_sender,
                    'alpha_code' => $B202_alpha_code,
                    'shipment_identification_number' => $B204_shipment_identification_number,
                    'method_payment' => $B206_method_payment,
                    'purpose_code' => $B2A01_purpose_code,
                    'reference_identification' => $L1103_reference_identification,
                    'reference_identification_qualifier' => $L1103_reference_identification_qualifier,
                    'stop_number_load' => $S501_stop_number_load,
                    'stop_reason_code_load' => $S502_stop_reason_code_load,
                    'weight_load' => $S503_weight_load,
                    'weight_units_load' => $S504_weight_units_load,
                    'quantity_load' => $S505_quantity_load,
                    'unit_for_measurement_load' => $S506_unit_for_measurement_load,
                    'load_date_1' => $G6202_load_date_1,
                    'load_time_1' => $G6204_load_time_1,
                    'load_time_code_1' => $G6205_load_time_code_1,
                    'load_date_qualifier_2' => $G6201_load_date_qualifier_2,
                    'load_date_2' => $G6202_load_date_2,
                    'load_time_qualifier_2' => $G6203_load_time_qualifier_2,
                    'load_time_2' => $G6204load_time_2,
                    'load_time_code_2' => $G6205_load_time_code_2,
                    'origin' => $N102_origin,
                    'addres_origin' => $N301_addres_origin,
                    'city_origin' => $N401_city_origin,
                    'state_origin' => $N402_state_origin,
                    'postal_code_origin' => $N403_postal_code_origin,
                    'country_origin' => $N404_country_origin,
                    'stop_number_stop1' => $S501_stop_number_stop1,
                    'stop_reason_code_stop1' => $S502_stop_reason_code_stop1,
                    'weight_stop1' => $S503_weight_stop1,
                    'weight_units_stop1' => $S504_weight_units_stop1,
                    'quantity_stop1' => $S505_quantity_stop1,
                    'unit_for_measurement_stop1' => $S506_unit_for_measurement_stop1,
                    'tracking_number_stop1' => $L1101_tracking_number,
                    'id_tracking_number' => $l1102_id_tracking_number,
                    'stop1_date' => $G6202_stop1_date,
                    'stop1_time' => $G6204_stop1_time,
                    'stop1_time_code' => $G6205_stop1_time_code,
                    'stop1' => $N102_stop1,
                    'addres_stop1' => $N301_addres_stop1,
                    'city_stop1' => $N401_city_stop1,
                    'state_stop1' => $N402_state_stop1,
                    'postal_code_stop1' => $N403_postal_code_stop1,
                    'country_stop1' => $N404_country_stop1, ]);
                    if (empty($save204)) { Log::warning('No se guardaron datos de txt204 SqlSrv'); } 
                    else { Log::info('Datos almacenados en SqlSrv!'); }
                $code = '0'; //es para usar la plantilla correo con markdown
                $id = $B204_shipment_identification_number;
                $origen = $N102_origin;
                $destino = $N102_stop1;
                $fecha = date('d/M/Y', strtotime($G6202_load_date_1));
                $hora = date('H:i', strtotime($G6204_load_time_1));
                $email = env('MAIL_SEND');
                Mail::to($email)->send(new NotificaDaimler($code, $id, $origen, $destino, $fecha, $hora));
                    Log::info('Correo enviado!');
                //inicia confirmacion de recibido 997
                $data997 = DB::connection('sqlsrv')->table("edi_daimler_997_send")->where('control_number_sender', '=', $ST02_control_number_sender)->first();
                if (empty($file997)) { Log::critical('No existen datos edi_daimler_997_send'); }
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
                        $filename = trim($data997->id_receiver).'_'.$data997->sender_code.'_997_'.date('Ymd', strtotime($data997->date_time)).'_'.$idnew;
                        //Crear archivo TxT 997
                        $file997 = Storage::disk('ftp')->put('toRyder/'.$filename.'.txt', "ISA*00*          *00*          *".$data997->id_qualifier_receiver."*".$data997->id_receiver."*".$data997->id_qualifier_sender."*".$data997->id_sender."*".date('ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$data997->version_number."*".$data997->control_number."*".$idnew."*0*T*^~GS*FA*".trim($data997->id_receiver)."*".$data997->sender_code."*".date('Ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*0001*".$data997->agency_code."*".$data997->industry_identifier."~ST*997*0001~AK1*SM*".$data997->control_number_sender."~AK9*".$data997->code."*".$id."*".$id."*".$id."~SE*4*0001~GE*1*".$id."~IEA*1*".$idnew."~");
                    if (empty($file997)) {
                        Log::error('Hubo fallos al crear archivo 997');
                    } else {
                        Log::info('Archivo 997 creado');
                        // cambiar valor a 0 para no volverlo a leer
                        $up997 = DB::connection('sqlsrv')->table("edi_daimler_997_send")->where([ ['id_incremental', '=', $id] ])->update(['send_txt' => '0']);
                        if (empty($up997)) { Log::warning('Hubo fallos al actualizar edi_daimler_997_send');
                        } else { Log::info('tabla edi_daimler_997_send actualizada'); }
                    }
                }
            }
//Validacion segun el purpose_code
            elseif ($B2A01_purpose_code == '05') { //Si es 05 Actualizar Fechas y notificar
                $val = DB::connection('sqlsrv')->table("edi_daimler")->where('shipment_identification_number', '=', $B204_shipment_identification_number)->first();
                if (empty($val)) { Log::warning('No existen datos edi_daimler'); } 
                else {
                    if ($val->load_date_1 === $G6202_load_date_1 and $val->load_time_1 === $G6204_load_time_1) {
                    }//Log::info('validacion 05 fecha/hora con iguales'); //No hacer nada
                    else {
                    //actualizar SqlSrv tabla edi_daimler txt204
                    $update05 = DB::connection('sqlsrv')->table("edi_daimler")->where([ ['shipment_identification_number', '=', $B204_shipment_identification_number] ])->update(['purpose_code' => '05','load_date_1' => $G6202_load_date_1,'load_time_1' => $G6204_load_time_1,'load_date_2' => $G6202_load_date_2,'load_time_2' => $G6204load_time_2,'stop1_date' => $G6202_stop1_date,'stop1_time' => $G6204_stop1_time]);
                    if (empty($update05)) { Log::critical('Fallo al actualizar datos purpose:05 de txt204'); }
                    else {
                        Log::info('pedido actualizado purpose:05');
                    //graba el nombre de archivo para no volver a leerlo
                        $save204_05 = DB::table('edidaimlers')->insert(['filename' => $filename, 'shipment_id' => $B204_shipment_identification_number.'-05','created_at' => $today->format('Y-m-d H:i:s'),'updated_at' => $today->format('Y-m-d H:i:s')]);
                        if (empty($save204_05)) { Log::warning('No se almaceno archivo txt204 Mysql'); } 
                            else { Log::info('Datos almacenados en MySql'); }
                    //Notificar por correo
                        $code = '5';
                        $id = $val->shipment_identification_number;
                        $origen = $val->origin;
                        $destino = $val->stop1;
                        $fecha = date('d/M/Y', strtotime($G6202_load_date_1));
                        $hora = date('H:i', strtotime($G6204_load_time_1));
                        $email = env('MAIL_SEND');
                        Mail::to($email)->send(new NotificaDaimler($code, $id, $origen, $destino, $fecha, $hora));
                            Log::info('Correo de Actualizacion fue enviado!!');
                        }
                    }
                }
            }
            elseif ($B2A01_purpose_code == '01') { //Si es 01 se Cancela pedido, actualizar y Notificar
                $val01 = DB::connection('sqlsrv')->table("edi_daimler")->where('shipment_identification_number', '=', $B204_shipment_identification_number)->first();
                if ($val01->purpose_code === '01') {
                    //Log::info('validacion 01 ya fue actualizado');//No hacer nada
                } else {
                    $update01 = DB::connection('sqlsrv')->table("edi_daimler")->where([ ['shipment_identification_number', '=', $B204_shipment_identification_number] ])->update(['purpose_code' => '01']);
                    if (empty($update01)) { Log::critical('Fallo al actualizar datos purpose:01 de txt204'); }
                    else {
                        Log::info('pedido actualizado purpose:05');
                    //graba el nombre de archivo para no volver a leerlo
                        $save204_01 = DB::table('edidaimlers')->insert(['filename' => $filename, 'shipment_id' => $B204_shipment_identification_number.'-01','created_at' => $today->format('Y-m-d H:i:s'),'updated_at' => $today->format('Y-m-d H:i:s')]);
                        if (empty($save204_01)) { Log::warning('No se almaceno archivo txt204 Mysql'); } 
                        else { Log::info('Datos almacenados en MySql'); }
                    //Notificar por correo
                        $code = '1';
                        $id = $val01->shipment_identification_number;
                        $origen = $val01->origin;
                        $destino = $val01->stop1;
                        $fecha = date('d/M/Y', strtotime($G6202_load_date_1));
                        $hora = date('H:i', strtotime($G6202_load_date_1));
                        $email = env('MAIL_SEND');
                            Mail::to($email)->send(new NotificaDaimler($code, $id, $origen, $destino, $fecha, $hora));
                            Log::info('Correo de Cancelacion enviado!!');
                        //buscamos en tabla 990 si existe actualizar(si respodieron)
                        $data990 = DB::connection('sqlsrv')->table("edi_daimler_990")->where('shipment_identification_number', '=', $B204_shipment_identification_number)->first();
                        if (empty($data990)) { }//si es null no hacer nada
                        else { //si existe actualizar el purpose_code a 01
                            $update990 = DB::connection('sqlsrv')->table("edi_daimler_990")->where([ ['shipment_identification_number', '=', $B204_shipment_identification_number] ])->update(['purpose_code' => '01']);
                            //validar update990
                            if (empty($data990)) { Log::warning('Fallo actualizacion edi_daimler_990 ');}
                            else { Log::info('Se actualizo edi_daimler_990 con exito!'); }
                        }
                    }
                }
            }
        }
        else { //No hay nuevo archivos fromRyder/RYD204
            Log::info('No se encontraron archivos RYD204');
            }
        }//Code #204
//validar formato archivos .txt y con Codigo #824
    elseif ( substr($filename,-4)==".txt" and substr($filename, 0, 16) == "fromRyder/RYD824") {
            //Validar si ya existe el archivo
            $buscar824 = DB::table('edidaimlers')->where('filename', $filename)->first();
            if (empty($buscar824)) {
                Log::info('Archivo:'.$filename);
                // Se procede a descargar archivo
                $local = 'public/storage/'.$filename; //ruta para almacenar
                    if (ftp_get($conn_id, $local, $filename, FTP_BINARY)) { //descarga
                        Log::info('Descarga archivo exitoso');
                            //elimina el archivo del directorio ftp
                            if (ftp_delete($conn_id, $filename)) {
                                Log::info($filename.': se elimino satisfactoriamente');                
                            } else {
                                Log::warning('No se pudo eliminar: '.$filename);
                            }
                        $path824 = file::get('public/storage/'.$filename);//lectura local
                        $array824 = explode("~", $path824); //array inicial
                            Log::info('Archivo separado en array ~');
                        $txt824count = count($array824);
                        for($i = 0; $i<$txt824count; $i++) {
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
                        $savefile824 = DB::table('edidaimlers')->insert(['filename' => $filename, 'shipment_id' => 'Code824','created_at' => $today->format('Y-m-d H:i:s'),'updated_at' => $today->format('Y-m-d H:i:s')]);
                        if (empty($savefile824)) { Log::warning('Archivo  Daimler 824 no se almaceno Mysql'); }
                        else { Log::info('Archivo Daimler 824 Almacenado Mysql'); }
                        //almacenar en SqlSrv
                        $save824 = DB::connection('sqlsrv')->table("edi_daimler_824_")->insert([
                            'shipment_identification_number' => $REF_shipment_identification_number,
                            'reference_identification' => $OTI_reference_identification,
                            'error_code' => $TED_error_code,
                            'message' => $TED_message,
                            'date' => $datetime,
                            'time' => $datetime,
                            'send_txt' => '1' ]);
                            if (empty($save824)) { Log::warning('No se guardaron datos de Daimler 824 SqlSrv'); }
                            else { Log::info('Datos Daimler 824 almacenados en SqlSrv!'); }
                        $code = '824'; //es para usar la plantilla correo con markdown
                        $id = $REF_shipment_identification_number;
                        $origen = $TED_error_code;
                        $destino = $TED_message;
                        $fecha = 'null'; //date('d/M/Y', strtotime($G6202_load_date_1));
                        $hora = 'null'; //date('H:i', strtotime($G6204_load_time_1));
                        $email = env('MAIL_SEND');
                        Mail::to($email)->send(new NotificaDaimler($code, $id, $origen, $destino, $fecha, $hora));
                            Log::info('Correo enviado!');
                    } else {
                        Log::error('Ha ocurrido un problema al descargar');
                    }
            }
        }//Code #824
    }
    }//if ftp
    else {
        Log::error('No se pudo conectar al FTP');
    }
    ftp_close($conn_id); // cerrar la conexión ftp
    }

}