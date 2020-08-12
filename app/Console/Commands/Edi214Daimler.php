<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Edi214Daimler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edi214:daimler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send code 214 clinet daimler';

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
        $sql214 = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_214")->where('send_txt', '=', '1')->get();
            foreach ($sql214 as $data214) {
            if ($data214 == true) { //si obtiene datos se preparan para el archivo
                $id = $data214->id_incremental;
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
                    $name214 = $data214->alpha_code.'_'.$data214->sender_code.'_214_'.date('Ymd', strtotime($data214->date_time)).'_'.$idnew;
                        //Crear archivo TxT 214
                    $filenew = Storage::disk('ftp')->put('toRyder/'.$name214.'.txt', "ISA*00*          *00*          *".$data214->id_qualifier_receiver."*".$data214->id_receiver."*".$data214->id_qualifier_sender."*".$data214->id_sender."*".date('ymd', strtotime($data214->date_time))."*".date('Hi', strtotime($data214->date_time))."*".$data214->version_number."*".$data214->control_number."*".$idnew."*0*P*^~GS*QM*".trim($data214->id_receiver)."*".$data214->sender_code."  *".date('Ymd', strtotime($data214->date_time))."*".date('Hi', strtotime($data214->date_time))."*".$data214->id_incremental."*".$data214->agency_code."*".$data214->industry_identifier."~ST*214*0001~B10*".$data214->reference_identification."*".$data214->shipment_identification_number."*".$data214->alpha_code."~LX*1~AT7*".$data214->status_code."*".$data214->reason_code."***".date('Ymd', strtotime($data214->date_time))."*".date('Hi', strtotime($data214->date_time))."*CT~MS1*".$data214->city."*".$data214->state."*".$data214->country."~MS2*".$data214->alpha_code."*".$data214->equipment."~L11*".$data214->tracking_number."*".$data214->id_tracking_number."~SE*8*0001~GE*1*".$data214->id_incremental."~IEA*1*".$idnew."~");
                        if (empty($filenew)) {
                            Log::error('Hubo fallos al crear archivo 214');
                        } else {
                            Log::info('Archivo 214 creado');
                            // cambiar valor a 0 para no volverlo a leer
                            $up = DB::connection(env('DB_DAIMLER'))->table("edi_daimler_214")->where([ ['id_incremental', '=', $id] ])->update(['send_txt' => '0']);
                                if (empty($up)) {
                                    Log::warning('Fallo actualizacion tabla edi_daimler_214');
                                } else {
                                    Log::info('tabla edi_daimler_214 actualizada');
                                }
                        }                    
            }    
        }
    }

}