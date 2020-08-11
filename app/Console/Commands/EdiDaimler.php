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
                $array = explode("S5",$path);
                    Log::info('Archivo separado en array S5');
                $count = count($array);
        //separacion por cada S5
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
                            $L11 = explode("*",$header[5]);
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
        if ($purpose_code == '00'){ //se procesa por primera vez
        //almacenar en mysql
            $savefile = DB::table('edidaimlers')->insert(['filename' => $filename, 'shipment_id' => $shipment_identification_number,'purpose_code' => $purpose_code,'s5total' => $count-1,'created_at' => $today->format('Y-m-d H:i:s'),'updated_at' => $today->format('Y-m-d H:i:s')]);
                if (empty($savefile)) { Log::warning('Nombre de archivo no se almaceno Mysql'); }
                else { Log::info('Archivo Almacenado Mysql'); }
                // almacenar en BD Sqlsrv segun la cantidad de S5 contenida en txt
                        if ($count == 3) {
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
                                'stop2_time_code' => $stop2_time_code]);
                        } elseif($count == 4) {
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
                                'stop3_time_code' => $stop3_time_code]);
                        } elseif($count == 5) {
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
                                'stop4_time_code' => $stop4_time_code]);
                        } elseif($count == 6) {
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
                                'stop5_time_code' => $stop5_time_code]);
                        } elseif($count == 7) {
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
                                'stop6_time_code' => $stop6_time_code]);
                        } elseif($count == 8) {
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
                                'stop7_time_code' => $stop7_time_code]);
                        } elseif($count == 9) {
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
                                'stop8_time_code' => $stop8_time_code]);
                        }
                        else {
                            Log::error('No hubo coincidencias en Array Count(3,4,5,6,7,8)');
                        }
            if (empty($save204)) { Log::warning('No se guardaron datos de txt204 SqlSrv'); } 
            else { // Log::info('Datos almacenados en SqlSrv!'); }
                $code = '0'; //es para usar la plantilla correo con markdown
                $id = $shipment_identification_number;
                $origen = $stop1;
                $destino = $stop2;
                $fecha = date('d/M/Y', strtotime($stop1_date));
                $hora = date('H:i', strtotime($stop1_time));
                $email = env('MAIL_SEND_DAIMLER');
                $ccmails = env('CCMAIL_SEND_DAIMLER');
                Mail::to($email)->cc($ccmails)->send(new NotificaDaimler($code, $id, $origen, $destino, $fecha, $hora));
                    Log::info('Correo enviado!');
                //inicia confirmacion de recibido 997
                $data997 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_997_send")->where('control_number_sender', '=', $control_number_sender)->first();
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
                        $filename = trim($data997->id_receiver).'_'.$data997->sender_code.'_997_'.date('Ymd', strtotime($data997->date_time)).'_'.$idnew;
                        //Crear archivo TxT 997
                        $file997 = Storage::disk('ftp')->put('toRyder/'.$filename.'.txt', "ISA*00*          *00*          *".$data997->id_qualifier_receiver."*".$data997->id_receiver."*".$data997->id_qualifier_sender."*".$data997->id_sender."*".date('ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$data997->version_number."*".$data997->control_number."*".$data997->$control_number_sender."*0*P*^~GS*FA*".trim($data997->id_receiver)."*".$data997->sender_code."*".date('Ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$idnew."*".$data997->agency_code."*".$data997->industry_identifier."~ST*997*".$idnew."~AK1*SM*".$data997->control_number_sender."~AK9*".$data997->code."*".$idnew."*".$idnew."*".$idnew."~SE*4*".$idnew."~GE*1*".$data997->$control_number_sender."~IEA*1*".$data997->$control_number_sender."~");
                    if (empty($file997)) {
                        Log::error('Hubo fallos al crear archivo 997');
                    } else {
                        Log::info('Archivo 997 creado');
                        // cambiar valor a 0 para no volverlo a leer
                        $up997 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_997_send")->where([ ['id_incremental', '=', $data997->id_incremental] ])->update(['send_txt' => '0']);
                        if (empty($up997)) { Log::warning('Hubo fallos al actualizar edi_daimler_997_send');
                        } else { Log::info('tabla edi_daimler_997_send actualizada'); }
                    }
                }
            }
        }
//Validacion segun el purpose_code
            elseif ($purpose_code == '05') { //Si es 05 Actualizar Fechas y notificar
                $val = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where('shipment_identification_number', '=', $shipment_identification_number)->first();
                if (empty($val)) { Log::warning('No existen datos edi_daimler_204'); } 
                else {
                //actualizar fechas SqlSrv tabla edi_daimler_204 txt204
                    if ($count == 3) {
                        $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_identification_number] ])->update([
                            'purpose_code' => '05',
                            'stop1_date' => $stop1_date,
                            'stop1_time' => $stop1_time,
                            'stop1_time_code' => $stop1_time_code,
                            'stop2_date' => $stop2_date,
                            'stop2_time' => $stop2_time,
                            'stop2_time_code' => $stop2_time_code]);
                    } elseif($count == 4) {
                        $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_identification_number] ])->update([
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
                        $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_identification_number] ])->update([
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
                        $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_identification_number] ])->update([
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
                        $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_identification_number] ])->update([
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
                        $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_identification_number] ])->update([
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
                        $update05 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_identification_number] ])->update([
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
                    if (empty($update05)) { Log::critical('Fallo al actualizar datos purpose:05 en tabla edi_daimler_204'); }
                    else {
                        Log::info('pedido actualizado purpose:05');
                    //graba el nombre de archivo para no volver a leerlo
                        $save204_05 = DB::table('edidaimlers')->insert(['filename' => $filename, 'shipment_id' => $shipment_identification_number,'purpose_code' => $purpose_code,'s5total' => $count-1,'created_at' => $today->format('Y-m-d H:i:s'),'updated_at' => $today->format('Y-m-d H:i:s')]);
                            if (empty($save204_05)) { Log::warning('No se almaceno archivo txt204 Mysql'); } 
                            else { Log::info('Datos almacenados en MySql'); }
                    //Notificar por correo
                        $code = '5';
                        $id = $val->shipment_identification_number;
                        $origen = $val->origin;
                        $destino = $val->stop1;
                        $fecha = date('d/M/Y', strtotime($stop1_date));
                        $hora = date('H:i', strtotime($stop1_time));
                        $email = env('MAIL_SEND_DAIMLER');
                        $ccmails = env('CCMAIL_SEND_DAIMLER');
                        Mail::to($email)->cc($ccmails)->send(new NotificaDaimler($code, $id, $origen, $destino, $fecha, $hora));
                            Log::info('Correo de Actualizacion fue enviado!!');
                        }
                    //inicia confirmacion de recibido 997
                    $data997 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_997_send")->where('control_number_sender', '=', $control_number_sender)->first();
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
                                $filename = trim($data997->id_receiver).'_'.$data997->sender_code.'_997_'.date('Ymd', strtotime($data997->date_time)).'_'.$idnew;
                                //Crear archivo TxT 997
                                $file997 = Storage::disk('ftp')->put('toRyder/'.$filename.'.txt', "ISA*00*          *00*          *".$data997->id_qualifier_receiver."*".$data997->id_receiver."*".$data997->id_qualifier_sender."*".$data997->id_sender."*".date('ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$data997->version_number."*".$data997->control_number."*".$data997->$control_number_sender."*0*P*^~GS*FA*".trim($data997->id_receiver)."*".$data997->sender_code."*".date('Ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$idnew."*".$data997->agency_code."*".$data997->industry_identifier."~ST*997*".$idnew."~AK1*SM*".$data997->control_number_sender."~AK9*".$data997->code."*".$idnew."*".$idnew."*".$idnew."~SE*4*".$idnew."~GE*1*".$data997->$control_number_sender."~IEA*1*".$data997->$control_number_sender."~");
                            if (empty($file997)) {
                                Log::error('Hubo fallos al crear archivo 997');
                            } else {
                                Log::info('Archivo 997 creado');
                                // cambiar valor a 0 para no volverlo a leer
                                $up997 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_997_send")->where([ ['id_incremental', '=', $data997->id_incremental] ])->update(['send_txt' => '0']);
                                if (empty($up997)) { Log::warning('Hubo fallos al actualizar edi_daimler_997_send');
                                } else { Log::info('tabla edi_daimler_997_send actualizada'); }
                            }
                        }
                    }
                }
            elseif ($purpose_code == '01') { //Si es 01 se Cancela pedido, actualizar y Notificar
                $val01 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where('shipment_identification_number', '=', $shipment_identification_number)->first();
                if ($val01->purpose_code === '01') {
                    //Log::info('validacion 01 ya fue actualizado');//No hacer nada
                } else {
                    $update01 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_204")->where([ ['shipment_identification_number', '=', $shipment_identification_number] ])->update(['purpose_code' => '01']);
                    if (empty($update01)) { Log::critical('Fallo al actualizar datos purpose:01 en tabla edi_daimler_204'); }
                    else {
                        Log::info('pedido actualizado purpose:05');
                    //graba el nombre de archivo para no volver a leerlo
                        $save204_01 = DB::table('edidaimlers')->insert(['filename' => $filename, 'shipment_id' => $shipment_identification_number,'purpose_code' => $purpose_code,'s5total' => $count-1,'created_at' => $today->format('Y-m-d H:i:s'),'updated_at' => $today->format('Y-m-d H:i:s')]);
                        if (empty($save204_01)) { Log::warning('No se almaceno archivo txt204 Mysql'); } 
                        else { Log::info('Datos almacenados en MySql'); }
                    //Notificar por correo
                        $code = '1';
                        $id = $val01->shipment_identification_number;
                        $origen = $val01->origin;
                        $destino = $val01->stop1;
                        $fecha = date('d/M/Y', strtotime($stop1_date));
                        $hora = date('H:i', strtotime($stop1_time));
                        $email = env('MAIL_SEND_DAIMLER');
                        $ccmails = env('CCMAIL_SEND_DAIMLER');
                            Mail::to($email)->cc($ccmails)->send(new NotificaDaimler($code, $id, $origen, $destino, $fecha, $hora));
                            Log::info('Correo de Cancelacion enviado!!');
                        //buscamos en tabla 990 si existe actualizar(si respodieron)
                        $data990 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_990")->where('shipment_identification_number', '=', $shipment_identification_number)->first();
                        if (empty($data990)) { }//si es null no hacer nada
                        else { //si existe actualizar el purpose_code a 01
                            $update990 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_990")->where([ ['shipment_identification_number', '=', $shipment_identification_number] ])->update(['purpose_code' => '01']);
                            //validar update990
                            if (empty($data990)) { Log::warning('Fallo actualizacion edi_daimler_990 ');}
                            else { Log::info('Se actualizo edi_daimler_990 con exito!'); }
                        }
                    //inicia confirmacion de recibido 997
                        $data997 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_997_send")->where('control_number_sender', '=', $control_number_sender)->first();
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
                                $filename = trim($data997->id_receiver).'_'.$data997->sender_code.'_997_'.date('Ymd', strtotime($data997->date_time)).'_'.$idnew;
                                //Crear archivo TxT 997
                                $file997 = Storage::disk('ftp')->put('toRyder/'.$filename.'.txt', "ISA*00*          *00*          *".$data997->id_qualifier_receiver."*".$data997->id_receiver."*".$data997->id_qualifier_sender."*".$data997->id_sender."*".date('ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$data997->version_number."*".$data997->control_number."*".$data997->$control_number_sender."*0*P*^~GS*FA*".trim($data997->id_receiver)."*".$data997->sender_code."*".date('Ymd', strtotime($data997->date_time))."*".date('Hi', strtotime($data997->date_time))."*".$idnew."*".$data997->agency_code."*".$data997->industry_identifier."~ST*997*".$idnew."~AK1*SM*".$data997->control_number_sender."~AK9*".$data997->code."*".$idnew."*".$idnew."*".$idnew."~SE*4*".$idnew."~GE*1*".$data997->$control_number_sender."~IEA*1*".$data997->$control_number_sender."~");
                            if (empty($file997)) {
                                Log::error('Hubo fallos al crear archivo 997');
                            } else {
                                Log::info('Archivo 997 creado');
                                // cambiar valor a 0 para no volverlo a leer
                                $up997 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_997_send")->where([ ['id_incremental', '=', $data997->id_incremental] ])->update(['send_txt' => '0']);
                                if (empty($up997)) { Log::warning('Hubo fallos al actualizar edi_daimler_997_send');
                                } else { Log::info('tabla edi_daimler_997_send actualizada'); }
                            }
                        }
                    }
                }
            }
            else{
                Log::info('Archivo 204: No se proceso con exito!');
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
                        $save824 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_824")->insert([
                            'shipment_identification_number' => $REF_shipment_identification_number,
                            'reference_identification' => $OTI_reference_identification,
                            'error_code' => $TED_error_code,
                            'message' => $TED_message,
                            'date' => $datetime,
                            'time' => $datetime,
                            'send_txt' => '1' ]);
                        if (empty($save824)) { Log::warning('No se guardaron datos de Daimler 824 SqlSrv'); }
                        else { //Log::info('Datos Daimler 824 almacenados en SqlSrv!'); }
                            $code = '824'; //es para usar la plantilla correo con markdown
                            $id = $REF_shipment_identification_number;
                            $origen = $TED_error_code;
                            $destino = $TED_message;
                            $fecha = 'null'; //date('d/M/Y', strtotime($G6202_load_date_1));
                            $hora = 'null'; //date('H:i', strtotime($G6204_load_time_1));
                            $email = env('MAIL_SEND_DAIMLER');
                            $ccmails = env('CCMAIL_SEND_DAIMLER');
                            Mail::to($email)->cc($ccmails)->send(new NotificaDaimler($code, $id, $origen, $destino, $fecha, $hora));
                                Log::info('Correo enviado!');
                        }
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