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
        $ftp_server = '168.218.18.135';
        $ftp_user = env('FTP_USERNAME');
        $ftp_pass = env('FTP_PASSWORD');

        // establecer una conexión o finalizarla
        $conn_id = ftp_connect($ftp_server) or die("No se pudo conectar a $ftp_server"); 
        $login = ftp_login($conn_id, $ftp_user, $ftp_pass);
        //validar conexion FtP
        if ( @$login ) {

        // Obtener los archivos contenidos en el directorio
        $files = ftp_nlist($conn_id, 'fromRyder');
        $cantidad = count($files);
        for($i=0; $i<$cantidad; $i++)
        {
            //validar Solo archivos TxT
            if ( substr($files[$i],-4)==".txt") {
            //validar archivos con nombre incial RYD203
            if (substr($files[$i], 0, 16) == "fromRyder/RYD204") {

                //Validar si ya existe el archivo
                $buscar = DB::table('edidaimlers')->where('filename', $files[$i])->first();

            if (empty($buscar)) {
                Log::info('Archivo:'.$files[$i]);
            // Se procede a descargar archivo
            //$conn = ftp_connect($ftp_server);
            $local = 'public/storage/'.$files[$i]; //ruta alamacenar            
            if (ftp_get($conn_id, $local, $files[$i], FTP_BINARY)) { //descarga
                Log::info('Descarga archivo exitoso');
            } else {
                echo "Ha habido un problema\n";
                Log::error('Ha ocurrido un problema al descargar');
            }
            
            $path = file::get('public/storage/'.$files[$i]);//lectura local
            $array = explode("~", $path); //array inicial       
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
            Log::info('Archivo Almacenado con exito!');
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
                $email = env('MAIL_SEND');
                Mail::to($email)->send(new NotificaDaimler($id, $origen, $destino, $fecha));
                Log::info('Correo enviado!!');
                //Log::info($email);

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
                $file997 = Storage::disk('ftp')->put('fromRyder/'.$filename.'.txt', "ISA*00*          *00*          *".$data997->id_qualifier_receiver."*".$data997->id_receiver."*".$data997->id_qualifier_sender."*".$data997->id_sender."*".date('ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$data997->version_number."*".$data997->control_number."*".$idnew."*0*T*^~GS*FA*".trim($data997->id_receiver)."*".$data997->sender_code."*".date('Ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*0001*".$data997->agency_code."*".$data997->industry_identifier."~ST*997*0001~AK1*SM*".$data997->control_number_sender."~AK9*".$data997->code."*".$id."*".$id."*".$id."~SE*4*0001~GE*1*".$id."~IEA*1*".$idnew."~");

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
    
    }//if ftp
    
        else {
            Log::error('No se pudo conectar al FTP');
        }
        // cerrar la conexión ftp
        ftp_close($conn_id);
    
    }

}
