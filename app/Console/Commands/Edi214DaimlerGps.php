<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $sql214gps = DB::connection('sqlsrv')->table("edi_daimler_214_gps")->where('send_txt', '=', '2')->get();
        if (count($sql214gps) !== 0) {
            foreach ($sql214gps as $data){
                $unidad = $data->unidad;
                $id = $data->id_incremental;
                $url = env('WEB_SERVICE_GPS');
                $server = file_get_contents($url);
                $file_headers = @get_headers($server);
                if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
                    Log::error('Web Service OffLine');
                }
                else {
                    $webservice = json_decode( file_get_contents($url.'/events/Data.jsonx?d='.$unidad.'&a=autofleteshalcon&u=web&p=web123&l=1'), true );
                        if (count($webservice) == 4) {
                            $listjson = $webservice['DeviceList'];
                            $datajson = $listjson[0]['EventData'];
                            $Latitud = substr($datajson[0]['GPSPoint_lat'], 0, -1);
                            $Longuitud = substr($datajson[0]['GPSPoint_lon'], 1,-1);
                    //Actualizar campos de gps en tabla sqlsrv
                            $updategps = DB::connection('sqlsrv')->table("edi_daimler_214_gps")->where([ ['id_incremental', '=', $id] ])->update(['longitude' => $Longuitud, 'latitude'=> $Latitud, 'send_txt' => '1']);
                                if (empty($updategps)) {
                                    Log::warning('Fallo actualizacion tabla edi_daimler_214_gps');
                                } else {
                                    Log::info('tabla edi_daimler_214_gps actualizada');
                                }
                        } else {
                            Log::warning('json sin datos de unidad: '. $unidad);
                        }
                    //Preprar TxT 214 GPS
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
                    $name214gps = $data->alpha_code.'_'.$data->sender_code.'_214_(testing)_'.date('Ymd', strtotime($data->date_time)).'_'.$idnew;
                    $ISA = "ISA*00*          *00*          *".$data->id_qualifier_receiver."*".$data->id_receiver."*".$data->id_qualifier_sender."*".$data->id_sender."*".date('ymd', strtotime($data->date_time))."*".date('Hi', strtotime($data->date_time))."*".$data->version_number."*".$data->control_number."*".$idnew."*0*T*^";
                    $GS = "GS*QM*".trim($data->id_receiver)."*".$data->sender_code."  *".date('Ymd', strtotime($data->date_time))."*".date('Hi', strtotime($data->date_time))."*".$data->id_incremental."*".$data->agency_code."*".$data->industry_identifier;
                    $ST = "ST*214*0001";
                    $B10 = "B10*".$data->reference_identification."*".$data->shipment_identification_number."*".$data->alpha_code;
                    $LX = "LX*1";
                    $AT7 = "AT7*".$data->status_code."*".$data->reason_code."***".date('Ymd', strtotime($data->date_time))."*".date('Hi', strtotime($data->date_time))."*CT";
                    $MS1 = "MS1****".$Longuitud."*".$Latitud."*".$data->code_longitude."*".$data->code_latitude."*";
                    $MS2 = "MS2*".$data->alpha_code."*".$data->equipment;
                    $SE = "SE*7*0001";
                    $GE = "GE*1*".$data->id_incremental;
                    $IEA = "IEA*1*".$idnew;
                    //Almacenar txt 214 en ftp Daimler
                    $filenew = Storage::disk('ftp')->put('toRyder/'.$name214gps.'.txt', $ISA."~".$GS."~".$ST."~".$B10."~".$LX."~".$AT7."~".$MS1."~".$MS2."~".$SE."~".$GE."~".$IEA."~");
                        if (empty($filenew)) { Log::error('Hubo fallos al crear archivo 214 GPS'); } 
                            else { Log::info('Archivo 214 GPS creado'); }
                }
            }
        }
    
    }
}
