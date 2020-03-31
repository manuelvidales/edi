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
        $ids = DB::table('edidaimlers')->select('shipment_id')->where('response', 'A')->get();
        foreach ($ids as $ship) {
        $sql214 = \DB::connection('sqlsrv')->table("edi_daimler_214")->where([ ['shipment_identification_number', '=', $ship->shipment_id],[ 'send_txt', '=', '0'] ])->get();
            foreach ($sql214 as $data214) {
            if ($data214 == true) { //si obtiene datos se crea el archivo
                $id = $data214->id_incremental;
                $i = strlen($id);
                    if ($i == 1) {
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
                $tr0td5 = $data214->id_qualifier_receiver;
                $tr0td6 = $data214->id_receiver;
                $tr0td7 = $data214->id_qualifier_sender;
                $tr0td8 = trim($data214->id_sender);//retira espacio en blanco
                $tr0td9 = date('ymd', strtotime($data214->date_time));
                $tr0td10 = date('Hi', strtotime($data214->date_time));
                $tr0td11 = $data214->version_number;
                $tr0td12 = $data214->control_number;
                $tr0td13 = $idnew;
                $tr1td2 = trim($data214->id_receiver);
                $tr1td3 = $data214->sender_code;
                $tr1td4 = date('Ymd', strtotime($data214->date_time));
                $tr1td5 = date('Hi', strtotime($data214->date_time));
                
                $tr1td6 = $data214->id_incremental; //revisar 4 digitos
                $tr1td7 = $data214->agency_code;
                $tr1td8 = $data214->industry_identifier;
                $tr3td1 = $data214->reference_identification;
                $tr3td2 = $data214->shipment_identification_number;
                $tr3td3 = $data214->alpha_code;
                $tr5td1 = $data214->status_code;
                $tr5td2 = $data214->reason_code;
                $tr5td5 = date('Ymd', strtotime($data214->date_time));
                $tr5td6 = date('His', strtotime($data214->date_time));
                $tr6td1 = $data214->city;
                $tr6td2 = $data214->state;
                $tr6td3 = $data214->country;
                $tr7td1 = $data214->alpha_code;
                $tr7td2 = $data214->equipment;
                $tr9td2 = $data214->weight_units_load;
                $tr9td3 = $data214->weight_load;
                $tr9td4 = $data214->quantity_load;
                $tr9td6 = $data214->volume_unit_qualifier_load;
                $tr9td7 = $data214->volume_load;

                $tr11td2 = $data214->id_incremental; //revisar 4 digitos
                $tr12td2 = $idnew; // 9 digitos
                $name214 = $data214->alpha_code.'_'.$data214->sender_code.'_214_'.$tr5td5.'_'.$idnew;

                    //Crear archivo TxT 214
                $filenew = Storage::disk('sftp')->put('files214/'.$name214.'.txt', "ISA*00*          *00*          *".$tr0td5."*".$tr0td6."*".$tr0td7."*".$tr0td8."*".$tr0td9."*".$tr0td10."*".$tr0td11."*".$tr0td12."*".$idnew."*0*T*^~GS*QM*".$tr1td2."*".$tr1td3."*".$tr1td4."*".$tr1td5."*".$tr1td6."*".$tr1td7."*".$tr1td8."~ST*214*0001~B10*".$tr3td1."*".$tr3td2."**".$tr3td3."~LX*1~AT7*".$tr5td1."*".$tr5td2."***".$tr5td5."*".$tr5td6."*CT~MS1*".$tr6td1."*".$tr6td2."*".$tr6td3."~MS2*".$tr7td1."*".$tr7td2."~L11*1*QN~AT8*G*".$tr9td2."*".$tr9td3."*".$tr9td4."**".$tr9td6."*".$tr9td7."~SE*9*0001~GE*1*".$tr11td2."~IEA*1*".$tr12td2."~");

                    if (empty($filenew)) {
                        Log::error('Hubo fallos al crear archivo 214');
                    } else {
                        Log::info('Archivo 214 creado');
                        // cambiar valor a 0 para no volverlo a leer
                        $up = DB::connection('sqlsrv')->table("edi_daimler_214")->where([ ['id_incremental', '=', $id] ])->update(['send_txt' => '0']);
                        Log::info('tabla edi_daimler_214 actualizada');
                    }                    
            }    
            }
        }
    }

}