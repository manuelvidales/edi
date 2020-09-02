<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\edidaimlertrack;

class Edi214DaimlerGps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edi214gps:daimler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send code 214_Gps daimler';

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
        $sql214gps = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_214_gps")->where('send_txt', '=', '1')->get();
        if (count($sql214gps) !== 0) {
            foreach ($sql214gps as $data){
                $unidad = $data->unidad;
                $id = $data->id_incremental;
                $url = env('WEB_SERVICE_GPS');
                $server = file_get_contents($url);
                $file_headers = @get_headers($server);
                if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                    Log::error('Web Service OffLine');
                } else {
                    $webservice = json_decode( file_get_contents($url.'/events/Data.jsonx?d='.$unidad.'&a=autofleteshalcon&u=web&p=web123&l=1'), true );
                    if (count($webservice) == 4) {
                        $listjson = $webservice['DeviceList'];
                        $datajson = $listjson[0]['EventData'];
                        $Lat1 = substr($datajson[0]['GPSPoint_lat'], 0, 7); //tomar hasta 7 caracteres
                        $Lon1 = substr($datajson[0]['GPSPoint_lon'], 1, 7); //tomar hasta 7 caracteres y quitar (-)
                            //contar caracteres
                            $Lat = strlen($Lat1);
                            $Lon = strlen($Lon1);
                            //validar caracteres
                            if ($Lat == 7) {
                                $Latitud = $Lat1;
                            } else {
                                // agregar ceros para completar 7 caracteres
                                if     ($Lat == 0) { $Latitud = '00.0000'; }
                                elseif ($Lat == 1) { $Latitud = '00.0000'; }
                                elseif ($Lat == 2) { $Latitud = $Lat1.'.0000'; }
                                elseif ($Lat == 3) { $Latitud = $Lat1.'0000'; }
                                elseif ($Lat == 4) { $Latitud = $Lat1.'000'; }
                                elseif ($Lat == 5) { $Latitud = $Lat1.'00'; }
                                elseif ($Lat == 6) { $Latitud = $Lat1.'0'; }
                            }
                            //validar caracteres
                            if ($Lon == 7) {
                                $Longitud = $Lon1;
                            } else {
                                // agregar ceros para completar 7 caracteres
                                if     ($Lon == 0) { $Longitud = '00.0000'; }
                                elseif ($Lon == 1) { $Longitud = '00.0000'; }
                                elseif ($Lon == 2) { $Longitud = $Lon1.'.0000'; }
                                elseif ($Lon == 3) { $Longitud = $Lon1.'.000'; }
                                elseif ($Lon == 4) { $Longitud = $Lon1.'000'; }
                                elseif ($Lon == 5) { $Longitud = $Lon1.'00'; }
                                elseif ($Lon == 6) { $Longitud = $Lon1.'0'; }
                            }
                        //Actualizar campos de gps en tabla sqlsrv
                        $updategps = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_214_gps")->where([ ['id_incremental', '=', $id] ])->update(['longitude' => $Longitud, 'latitude'=> $Latitud]);
                            //validar el update
                            if (empty($updategps)) {
                                Log::warning('Fallo actualizacion tabla edi_daimler_214_gps');
                            } else {
                                Log::info('tabla edi_daimler_214_gps actualizada');
                            }
                        //Preparar TxT 214 GPS
                        $i = strlen($id); //se requiere id de 9 digitos por eso se cuentan los caracteres
                        if     ($i == 1) { $idnew = '00000000'.$id; } // si tiene un caracter se le agregan 8 ceros
                        elseif ($i == 2) { $idnew = '0000000'.$id; } //sucesivamente
                        elseif ($i == 3) { $idnew = '000000'.$id; }
                        elseif ($i == 4) { $idnew = '00000'.$id; }
                        elseif ($i == 5) { $idnew = '0000'.$id; }
                        elseif ($i == 6) { $idnew = '000'.$id; }
                        elseif ($i == 7) { $idnew = '00'.$id; }
                        elseif ($i == 8) { $idnew = '0'.$id; }
                        elseif ($i == 9) { $idnew = $id; }
                        else { $idnew = 'null'; }
                        //nombre para el archivo 214
                        $name214gps = $data->alpha_code.'_'.$data->sender_code.'_214_'.date('Ymd', strtotime($data->date_time)).'_'.$idnew;
                        //preparacion campos para el archivo
                        $ISA = "ISA*00*          *00*          *".$data->id_qualifier_receiver."*".$data->id_receiver."*".$data->id_qualifier_sender."*".$data->id_sender."*".date('ymd', strtotime($data->date_time))."*".date('Hi', strtotime($data->date_time))."*".$data->version_number."*".$data->control_number."*".$idnew."*0*P*^";
                        $GS = "GS*QM*".trim($data->id_receiver)."*".$data->sender_code."  *".date('Ymd', strtotime($data->date_time))."*".date('Hi', strtotime($data->date_time))."*".$data->id_incremental."*".$data->agency_code."*".$data->industry_identifier;
                        $ST = "ST*214*0001";
                        $B10 = "B10*".$data->reference_identification."*".$data->shipment_identification_number."*".$data->alpha_code;
                        $LX = "LX*1";
                        $AT7 = "AT7*".$data->status_code."*".$data->reason_code."***".date('Ymd', strtotime($data->date_time))."*".date('Hi', strtotime($data->date_time))."*CT";
                        $MS1 = "MS1****".$Longitud."*".$Latitud."*".$data->code_longitude."*".$data->code_latitude."*";
                        $MS2 = "MS2*".$data->alpha_code."*".$data->equipment;
                        $SE = "SE*7*0001";
                        $GE = "GE*1*".$data->id_incremental;
                        $IEA = "IEA*1*".$idnew;
                        //Guardar datos a enviar para archivo 214 gps
                        $datatrack = new edidaimlertrack();
                        $datatrack->save214gps($data,$name214gps,$Longitud,$Latitud);
                        //Crear archivo 214 Ftp del Cliente
                        $file214ftp = Storage::disk('ftp')->put('toRyder/'.$name214gps.'.txt', $ISA."~".$GS."~".$ST."~".$B10."~".$LX."~".$AT7."~".$MS1."~".$MS2."~".$SE."~".$GE."~".$IEA."~");
                        //Crear archivo 214 Local
                        $file214local = Storage::disk('local')->put('Daimler/toRyder214gps/'.$name214gps.'.txt', $ISA."~".$GS."~".$ST."~".$B10."~".$LX."~".$AT7."~".$MS1."~".$MS2."~".$SE."~".$GE."~".$IEA."~");
                        //Validar la creacion
                            if (empty($file214ftp)) {
                                Log::error('fallos al crear archivo 214 GPS Ftp');
                            } elseif (empty($file214local)){
                                Log::error('fallos al crear archivo 214 GPS Local');
                            } else {
                                Log::info('Archivo 214 GPS creado');
                            }
                    } else {
                        Log::warning('json sin datos de unidad: '. $unidad);
                    }
                }
            }
        }
    }
}
