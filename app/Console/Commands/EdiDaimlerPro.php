<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\edidaimler;
use App\Mail\NotificaDaimler;


class EdiDaimlerPro extends Command
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
        // PROCESAR ARCHIVOS 204 y 824
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
                                    $contS5uno = count($S5_uno);
                                    for($s=0; $s<$contS5uno; $s++){
                                        $data1 = substr($S5_uno[$s],0,3);                    
                                        if ($data1 == '*1*') {
                                            $S5_1 = explode("*",$S5_uno[$s]);
                                        }elseif ($data1 == 'L11') { //El primero de dos
                                            $L11_1 = explode("*",$S5_uno[1]);
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
                                            $L11_2 = explode("*",$S5_dos[1]);
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
                                            $L11_3 = explode("*",$S5_tres[1]);
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
                                    $cont4 = count($S5_cuatro);
                                    for($s=0; $s<$cont4; $s++){
                                        $data4 = substr($S5_cuatro[$s],0,3);                    
                                        if ($data4 == '*4*') {
                                            $S5_4 = explode("*",$S5_cuatro[$s]);
                                        }elseif ($data4 == 'L11') { //El primero de dos
                                            $L11_4 = explode("*",$S5_cuatro[1]);
                                        }elseif ($data4 == 'G62') { //El primero de dos
                                            $G62_4 = explode("*",$S5_cuatro[$s]);
                                        }elseif ($data4 == 'N1*') {
                                            $N1_4 = explode("*",$S5_cuatro[$s]);
                                        }elseif ($data4 == 'N3*') {
                                            $N3_4 = explode("*",$S5_cuatro[$s]);
                                        }elseif ($data4 == 'N4*') {
                                            $N4_4 = explode("*",$S5_cuatro[$s]);
                                        }
                                    }
                                    //S5
                                        if (empty($S5_4)){
                                            $stop4_reason_code='null';
                                        }else{
                                            $stop4_reason_code=$S5_4[2];
                                        }
                                    //L11
                                        if (empty($L11_4)){
                                            $stop4_tracking_number='null';
                                        }else{
                                            $stop4_tracking_number=$L11_4[1];
                                        }
                                    //G62
                                        if (empty($G62_4)){
                                            $stop4_date='null';
                                            $stop4_time='null';
                                            $stop4_time_code='null';
                                        }else{
                                            $stop4_date=$G62_4[2];
                                            $stop4_time=$G62_4[4];
                                            $stop4_time_code=$G62_4[5];
                                        }
                                    //N1
                                        if (empty($N1_4)){
                                            $stop4_identifier_code='null';
                                            $stop4='null';
                                        }else{
                                            $stop4_identifier_code=$N1_4[1];
                                            $stop4=$N1_4[2];
                                        }
                                    //N3
                                        if (empty($N3_4)){
                                            $stop4_addres='null';
                                        }else{
                                            $stop4_addres=$N3_4[1];
                                        }
                                    //N4
                                        if (empty($N4_4)){
                                            $stop4_city='null';
                                            $stop4_state='null';
                                            $stop4_postal_code='null';
                                            $stop4_country='null';
                                        }else{
                                            $stop4_city=$N4_4[1];
                                            $stop4_state=$N4_4[2];
                                            $stop4_postal_code=$N4_4[3];
                                            $stop4_country=$N4_4[4];
                                        }
                                break;
                                case "*5*":
                                    $S5_cinco = explode("~",$array[$i]);
                                    $cont5 = count($S5_cinco);
                                    for($s=0; $s<$cont5; $s++){
                                        $data5 = substr($S5_cinco[$s],0,3);                    
                                        if ($data5 == '*5*') {
                                            $S5_5 = explode("*",$S5_cinco[$s]);
                                        }elseif ($data5 == 'L11') { //El primero de dos
                                            $L11_5 = explode("*",$S5_cinco[1]);
                                        }elseif ($data5 == 'G62') { //El primero de dos
                                            $G62_5 = explode("*",$S5_cinco[$s]);
                                        }elseif ($data5 == 'N1*') {
                                            $N1_5 = explode("*",$S5_cinco[$s]);
                                        }elseif ($data5 == 'N3*') {
                                            $N3_5 = explode("*",$S5_cinco[$s]);
                                        }elseif ($data5 == 'N4*') {
                                            $N4_5 = explode("*",$S5_cinco[$s]);
                                        }
                                    }
                                    //S5
                                        if (empty($S5_5)){
                                            $stop5_reason_code='null';
                                        }else{
                                            $stop5_reason_code=$S5_5[2];
                                        }
                                    //L11
                                        if (empty($L11_5)){
                                            $stop5_tracking_number='null';
                                        }else{
                                            $stop5_tracking_number=$L11_5[1];
                                        }
                                    //G62
                                        if (empty($G62_5)){
                                            $stop5_date='null';
                                            $stop5_time='null';
                                            $stop5_time_code='null';
                                        }else{
                                            $stop5_date=$G62_5[2];
                                            $stop5_time=$G62_5[4];
                                            $stop5_time_code=$G62_5[5];
                                        }
                                    //N1
                                        if (empty($N1_5)){
                                            $stop5_identifier_code='null';
                                            $stop5='null';
                                        }else{
                                            $stop5_identifier_code=$N1_5[1];
                                            $stop5=$N1_5[2];
                                        }
                                    //N3
                                        if (empty($N3_5)){
                                            $stop5_addres='null';
                                        }else{
                                            $stop5_addres=$N3_5[1];
                                        }
                                    //N4
                                        if (empty($N4_5)){
                                            $stop5_city='null';
                                            $stop5_state='null';
                                            $stop5_postal_code='null';
                                            $stop5_country='null';
                                        }else{
                                            $stop5_city=$N4_5[1];
                                            $stop5_state=$N4_5[2];
                                            $stop5_postal_code=$N4_5[3];
                                            $stop5_country=$N4_5[4];
                                        }
                                break;
                                case "*6*":
                                    $S5_seis = explode("~",$array[$i]);
                                    $cont6 = count($S5_seis);
                                    for($s=0; $s<$cont6; $s++){
                                        $data6 = substr($S5_seis[$s],0,3);                    
                                        if ($data6 == '*6*') {
                                            $S5_6 = explode("*",$S5_seis[$s]);
                                        }elseif ($data6 == 'L11') { //El primero de dos
                                            $L11_6 = explode("*",$S5_seis[1]);
                                        }elseif ($data6 == 'G62') { //El primero de dos
                                            $G62_6 = explode("*",$S5_seis[$s]);
                                        }elseif ($data6 == 'N1*') {
                                            $N1_6 = explode("*",$S5_seis[$s]);
                                        }elseif ($data6 == 'N3*') {
                                            $N3_6 = explode("*",$S5_seis[$s]);
                                        }elseif ($data6 == 'N4*') {
                                            $N4_6 = explode("*",$S5_seis[$s]);
                                        }
                                    }
                                    //S5
                                        if (empty($S5_6)){
                                            $stop6_reason_code='null';
                                        }else{
                                            $stop6_reason_code=$S5_6[2];
                                        }
                                    //L11
                                        if (empty($L11_6)){
                                            $stop6_tracking_number='null';
                                        }else{
                                            $stop6_tracking_number=$L11_6[1];
                                        }
                                    //G62
                                        if (empty($G62_6)){
                                            $stop6_date='null';
                                            $stop6_time='null';
                                            $stop6_time_code='null';
                                        }else{
                                            $stop6_date=$G62_6[2];
                                            $stop6_time=$G62_6[4];
                                            $stop6_time_code=$G62_6[5];
                                        }
                                    //N1
                                        if (empty($N1_6)){
                                            $stop6_identifier_code='null';
                                            $stop6='null';
                                        }else{
                                            $stop6_identifier_code=$N1_6[1];
                                            $stop6=$N1_6[2];
                                        }
                                    //N3
                                        if (empty($N3_6)){
                                            $stop6_addres='null';
                                        }else{
                                            $stop6_addres=$N3_6[1];
                                        }
                                    //N4
                                        if (empty($N4_6)){
                                            $stop6_city='null';
                                            $stop6_state='null';
                                            $stop6_postal_code='null';
                                            $stop6_country='null';
                                        }else{
                                            $stop6_city=$N4_6[1];
                                            $stop6_state=$N4_6[2];
                                            $stop6_postal_code=$N4_6[3];
                                            $stop6_country=$N4_6[4];
                                        }
                                break;
                                case "*7*":
                                    $S5_siete = explode("~",$array[$i]);
                                    $cont7 = count($S5_siete);
                                    for($s=0; $s<$cont7; $s++){
                                        $data7 = substr($S5_siete[$s],0,3);                    
                                        if ($data7 == '*7*') {
                                            $S5_7 = explode("*",$S5_siete[$s]);
                                        }elseif ($data7 == 'L11') { //El primero de dos
                                            $L11_7 = explode("*",$S5_siete[1]);
                                        }elseif ($data7 == 'G62') { //El primero de dos
                                            $G62_7 = explode("*",$S5_siete[$s]);
                                        }elseif ($data7 == 'N1*') {
                                            $N1_7 = explode("*",$S5_siete[$s]);
                                        }elseif ($data7 == 'N3*') {
                                            $N3_7 = explode("*",$S5_siete[$s]);
                                        }elseif ($data7 == 'N4*') {
                                            $N4_7 = explode("*",$S5_siete[$s]);
                                        }
                                    }
                                    //S5
                                        if (empty($S5_7)){
                                            $stop7_reason_code='null';
                                        }else{
                                            $stop7_reason_code=$S5_7[2];
                                        }
                                    //L11
                                        if (empty($L11_7)){
                                            $stop7_tracking_number='null';
                                        }else{
                                            $stop7_tracking_number=$L11_7[1];
                                        }
                                    //G62
                                        if (empty($G62_7)){
                                            $stop7_date='null';
                                            $stop7_time='null';
                                            $stop7_time_code='null';
                                        }else{
                                            $stop7_date=$G62_7[2];
                                            $stop7_time=$G62_7[4];
                                            $stop7_time_code=$G62_7[5];
                                        }
                                    //N1
                                        if (empty($N1_7)){
                                            $stop7_identifier_code='null';
                                            $stop7='null';
                                        }else{
                                            $stop7_identifier_code=$N1_7[1];
                                            $stop7=$N1_7[2];
                                        }
                                    //N3
                                        if (empty($N3_7)){
                                            $stop7_addres='null';
                                        }else{
                                            $stop7_addres=$N3_7[1];
                                        }
                                    //N4
                                        if (empty($N4_7)){
                                            $stop7_city='null';
                                            $stop7_state='null';
                                            $stop7_postal_code='null';
                                            $stop7_country='null';
                                        }else{
                                            $stop7_city=$N4_7[1];
                                            $stop7_state=$N4_7[2];
                                            $stop7_postal_code=$N4_7[3];
                                            $stop7_country=$N4_7[4];
                                        }
                                break;
                                case "*8*":
                                    $S5_ocho = explode("~",$array[$i]);
                                    $cont8 = count($S5_ocho);
                                    for($s=0; $s<$cont8; $s++){
                                        $data8 = substr($S5_ocho[$s],0,3);                    
                                        if ($data8 == '*8*') {
                                            $S5_8 = explode("*",$S5_ocho[$s]);
                                        }elseif ($data8 == 'L11') { //El primero de dos
                                            $L11_8 = explode("*",$S5_ocho[1]);
                                        }elseif ($data8 == 'G62') { //El primero de dos
                                            $G62_8 = explode("*",$S5_ocho[$s]);
                                        }elseif ($data8 == 'N1*') {
                                            $N1_8 = explode("*",$S5_ocho[$s]);
                                        }elseif ($data8 == 'N3*') {
                                            $N3_8 = explode("*",$S5_ocho[$s]);
                                        }elseif ($data8 == 'N4*') {
                                            $N4_8 = explode("*",$S5_ocho[$s]);
                                        }
                                    }
                                    //S5
                                        if (empty($S5_8)){
                                            $stop8_reason_code='null';
                                        }else{
                                            $stop8_reason_code=$S5_8[2];
                                        }
                                    //L11
                                        if (empty($L11_8)){
                                            $stop8_tracking_number='null';
                                        }else{
                                            $stop8_tracking_number=$L11_8[1];
                                        }
                                    //G62
                                        if (empty($G62_8)){
                                            $stop8_date='null';
                                            $stop8_time='null';
                                            $stop8_time_code='null';
                                        }else{
                                            $stop8_date=$G62_8[2];
                                            $stop8_time=$G62_8[4];
                                            $stop8_time_code=$G62_8[5];
                                        }
                                    //N1
                                        if (empty($N1_8)){
                                            $stop8_identifier_code='null';
                                            $stop8='null';
                                        }else{
                                            $stop8_identifier_code=$N1_8[1];
                                            $stop8=$N1_8[2];
                                        }
                                    //N3
                                        if (empty($N3_8)){
                                            $stop8_addres='null';
                                        }else{
                                            $stop8_addres=$N3_8[1];
                                        }
                                    //N4
                                        if (empty($N4_8)){
                                            $stop8_city='null';
                                            $stop8_state='null';
                                            $stop8_postal_code='null';
                                            $stop8_country='null';
                                        }else{
                                            $stop8_city=$N4_8[1];
                                            $stop8_state=$N4_8[2];
                                            $stop8_postal_code=$N4_8[3];
                                            $stop8_country=$N4_8[4];
                                        }
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
            } //for
        } else { //buscar los que tiene algun error y procesar segun su purpose_code
            $status3 = DB::table('edidaimlers')->where('status', '3')->get();
            if (count($status3) == 0) {
                $status2 = DB::table('edidaimlers')->where('status', '2')->get();
                if (count($status2) == 0) { //No hay ninguno para procesar
                } else {
                    foreach ($status2 as $data) {
                        Log::info('Archivo en proceso: '.$data->id); //mostrar los procesados
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
    }
}