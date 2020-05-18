<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificaVisteon;

class EdiVisteon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edi210:visteon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send invoice client Visteon';

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
        $sql = \DB::connection('sqlsrvpro')->table("edi_210")->get();
        if (empty($sql)) {
            Log::error('problemas con sqlsrv tabla edi_210');
        }else{
            foreach ($sql as $row) {
                if ($row->send_txt == '0') { //no hacer nada...
                    Log::info('No existen nuevos registros de Visteon!');
                }elseif ($row->send_txt == '1') {
                    $id = $row->id_incremental;
                    $i = strlen($id);//cantidad de caracteres
                        if ($i == 1) {
                            $idnew = '00000000'.$id;
                        } elseif ($i == 2) {
                            $idnew = '0000000'.$id;
                        } elseif ($i == 3) {
                            $idnew = '000000'.$id;
                        } elseif ($i == 4) {
                            $idnew = '00000'.$id;
                        } elseif ($i == 5) {
                            $idnew = '0000'.$id;
                        } elseif ($i == 6) {
                            $idnew = '000'.$id;
                        } elseif ($i == 7) {
                            $idnew = '00'.$id;
                        } elseif ($i == 8) {
                            $idnew = '0'.$id;
                        } elseif ($i == 9) {
                            $idnew = $id;
                        } else{
                            $idnew = 'null';
                        }
                    $filename1 = $row->B311_alpha_code.'_VISTEON_210_'.date('Ymd', strtotime($row->B309_dalivery_date)).'_'.$idnew;
                    $file1 = Storage::disk('localftp')->put('/'.$filename1.'.txt', "ISA*00*          *00*          *02*".$row->B311_alpha_code."           *01*VISTEON        *".date('ymd', strtotime($row->B309_dalivery_date))."*1138*U*00401*".$idnew."*0*T*^~GS*IM*".$row->B311_alpha_code."*VISTEON*".date('Ymd', strtotime($row->B309_dalivery_date))."*1138*".$id."*X*004010~ST*210*0001~B3**".$row->B302_invoice."*".$row->B303_shipment_identification_number."*".$row->B304_shipment_method_of_payment."**".date('Ymd', strtotime($row->B306_date))."*".$row->B307_net_amount_due."**".date('Ymd', strtotime($row->B309_dalivery_date))."*".$row->B310_datetime_qualifier."*".$row->B311_alpha_code."~C3*".$row->C301_currency_code."*".$row->C302_exchange_rate."~N9*".$row->N901_1_reference_identification_qualifier."*".$row->N902_1_reference_identification."*".$row->N903_1_description."~N9*".$row->N901_2_reference_identification_qualifier."*".$row->N902_2_reference_identification."~N9*".$row->N901_3_reference_identification_qualifier."*".$row->N902_3_reference_identification."~N9*".$row->N901_4_reference_identification_qualifier."*".$row->N902_4_reference_identification."*".$row->N903_4_description."~G62*".$row->G6201_1_date_qualifier."*".date('Ymd', strtotime($row->G6202_1_date))."~G62*".$row->G6201_2_date_qualifier."*".date('Ymd', strtotime($row->G6202_2_date))."~K1*".$row->K101_message."*".date('Ymd', strtotime($row->K102_message))."~N1*".$row->N101_1_identifier_code."*".$row->N102_1_name."~N3*".$row->N301_1_address."~N4*".$row->N401_1_city."*".$row->N402_1_state."*".$row->N403_1_postal_code."*".$row->N404_1_country."~N1*".$row->N101_2_identifier_code."*".$row->N102_2_name."~N3*".$row->N301_2_address."~N4*".$row->N401_2_city."*".$row->N402_2_state."*".$row->N403_2_postal_code."*".$row->N404_2_country."~N1*".$row->N101_3_identifier_code."*".$row->N102_3_name."~N3*".$row->N301_3_address."~N4*".$row->N401_3_city."*".$row->N402_3_state."*".$row->N403_3_postal_code."*".$row->N404_3_country."~N7**".$row->N702_equipment_number."*********".$row->N711_equipment_description_code."~LX*".$row->LX_1_assigned_number."~L5*".$row->L501_1_item_number."*".$row->L502_1_lading_description."~L1*".$row->L101_1_item_number."*".$row->L102_1_freight_rate."*".$row->L103_1_rate_qualifier."*".$row->L104_1_charge."~L7*".$row->L701_1_item_number."******".$row->L707_1_freight_class_code."~LX*".$row->LX_2_assigned_number."~L5*".$row->L501_2_item_number."*".$row->L502_2_lading_description."~L1*".$row->L101_2_item_number."***".$row->L104_2_charge."****".$row->L108_2_special_charge."~LX*".$row->LX_3_assigned_number."~L5*".$row->L501_3_item_number."*".$row->L502_3_lading_description."~L1*".$row->L101_3_item_number."***".$row->L104_3_charge."****".$row->L108_3_special_charge."~LX*".$row->LX_4_assigned_number."~L5*".$row->L501_4_item_number."*".$row->L502_4_lading_description."~L1*".$row->L101_4_item_number."***-".$row->L104_4_charge."****".$row->L108_4_special_charge."~L3*****".$row->L305_charge."~SE*35*0001~GE*1*".$id."~IEA*1*".$idnew."~");
                    //validaciones
                    if (empty($file1)) {
                        Log::error('Hubo fallos al crear archivo txt');
                    } else {
                        Log::info('Archivo creado con exito!');
                        $id = $row->B302_invoice;
                        $fecha = date('d/m/Y', strtotime($row->B309_dalivery_date));
                        $email = env('MAIL_SEND_VISTEON');
                        $ccmails = env('CCMAIL_SEND_VISTEON');
                        $cc = explode(',', $ccmails);
                        Mail::to($email)->cc([$cc[0],$cc[1]])->send(new NotificaVisteon($id, $fecha));
                        Log::info('Correo enviado!!');
                        //Actualizar Send_txt Valor a 0
                        $update = DB::connection('sqlsrvpro')->table("edi_210")->where('id_incremental',$row->id_incremental)->update(['send_txt' => '0']);
                            if (empty($update)) {
                                Log::error('Error al Actualizar tabla edi_210 con Send_txt a 0');
                            } else {
                                Log::info('tabla edi_210 actualizada');
                            }
                    }
                } elseif($row->send_txt == '2') {
                    $id = $row->id_incremental;
                    $i = strlen($id);//cantidad de caracteres
                        if ($i == 1) {
                            $idnew = '00000000'.$id;
                        } elseif ($i == 2) {
                            $idnew = '0000000'.$id;
                        } elseif ($i == 3) {
                            $idnew = '000000'.$id;
                        } elseif ($i == 4) {
                            $idnew = '00000'.$id;
                        } elseif ($i == 5) {
                            $idnew = '0000'.$id;
                        } elseif ($i == 6) {
                            $idnew = '000'.$id;
                        } elseif ($i == 7) {
                            $idnew = '00'.$id;
                        } elseif ($i == 8) {
                            $idnew = '0'.$id;
                        } elseif ($i == 9) {
                            $idnew = $id;
                        } else{
                            $idnew = 'null';
                        }
                    $filename2 = $row->B311_alpha_code.'_VISTEON_210_'.date('Ymd', strtotime($row->B309_dalivery_date)).'_'.$idnew;
                    $file2 = Storage::disk('localftp')->put('/'.$filename2.'.txt', "ISA*00*          *00*          *02*".$row->B311_alpha_code."           *01*VISTEON        *".date('ymd', strtotime($row->B309_dalivery_date))."*1138*U*00401*".$idnew."*0*T*^~GS*IM*".$row->B311_alpha_code."*VISTEON*".date('Ymd', strtotime($row->B309_dalivery_date))."*1138*".$id."*X*004010~ST*210*0001~B3**".$row->B302_invoice."*".$row->B303_shipment_identification_number."*".$row->B304_shipment_method_of_payment."**".date('Ymd', strtotime($row->B306_date))."*".$row->B307_net_amount_due."**".date('Ymd', strtotime($row->B309_dalivery_date))."*".$row->B310_datetime_qualifier."*".$row->B311_alpha_code."~C3*".$row->C301_currency_code."*".$row->C302_exchange_rate."~N9*".$row->N901_1_reference_identification_qualifier."*".$row->N902_1_reference_identification."*".$row->N903_1_description."~N9*".$row->N901_2_reference_identification_qualifier."*".$row->N902_2_reference_identification."~N9*".$row->N901_3_reference_identification_qualifier."*".$row->N902_3_reference_identification."~N9*".$row->N901_4_reference_identification_qualifier."*".$row->N902_4_reference_identification."*".$row->N903_4_description."~G62*".$row->G6201_1_date_qualifier."*".date('Ymd', strtotime($row->G6202_1_date))."~G62*".$row->G6201_2_date_qualifier."*".date('Ymd', strtotime($row->G6202_2_date))."~K1*".$row->K101_message."*".date('Ymd', strtotime($row->K102_message))."~N1*".$row->N101_1_identifier_code."*".$row->N102_1_name."~N3*".$row->N301_1_address."~N4*".$row->N401_1_city."*".$row->N402_1_state."*".$row->N403_1_postal_code."*".$row->N404_1_country."~N1*".$row->N101_2_identifier_code."*".$row->N102_2_name."~N3*".$row->N301_2_address."~N4*".$row->N401_2_city."*".$row->N402_2_state."*".$row->N403_2_postal_code."*".$row->N404_2_country."~N1*".$row->N101_3_identifier_code."*".$row->N102_3_name."~N3*".$row->N301_3_address."~N4*".$row->N401_3_city."*".$row->N402_3_state."*".$row->N403_3_postal_code."*".$row->N404_3_country."~N7**".$row->N702_equipment_number."*********".$row->N711_equipment_description_code."~LX*".$row->LX_1_assigned_number."~L5*".$row->L501_1_item_number."*".$row->L502_1_lading_description."~L1*".$row->L101_1_item_number."*".$row->L102_1_freight_rate."*".$row->L103_1_rate_qualifier."*".$row->L104_1_charge."~L7*".$row->L701_1_item_number."******".$row->L707_1_freight_class_code."~LX*".$row->LX_2_assigned_number."~L5*".$row->L501_2_item_number."*".$row->L502_2_lading_description."~L1*".$row->L101_2_item_number."***".$row->L104_2_charge."****".$row->L108_2_special_charge."~L3*****".$row->L305_charge."~SE*29*0001~GE*1*".$id."~IEA*1*".$idnew."~");
                    //validaciones
                    if (empty($file2)) {
                        Log::error('Hubo fallos al crear archivo txt');
                    } else {
                        Log::info('Archivo creado con exito!');
                        $id = $row->B302_invoice;
                        $fecha = date('d/m/Y', strtotime($row->B309_dalivery_date));
                        $email = env('MAIL_SEND_VISTEON');
                        $ccmails = env('CCMAIL_SEND_VISTEON');
                        $cc = explode(',', $ccmails);
                        Mail::to($email)->cc([$cc[0],$cc[1],$cc[2],$cc[3]])->send(new NotificaVisteon($id, $fecha));
                        Log::info('Correo enviado!!');
                        //Actualizar Send_txt Valor a 0
                        $update = DB::connection('sqlsrvpro')->table("edi_210")->where('id_incremental',$row->id_incremental)->update(['send_txt' => '0']);
                            if (empty($update)) {
                                Log::error('Error al Actualizar tabla edi_210 con Send_txt a 0');
                            } else {
                                Log::info('tabla edi_210 actualizada');
                            }
                    }
                } elseif($row->send_txt == '3') {
                    $id = $row->id_incremental;
                    $i = strlen($id);//cantidad de caracteres
                        if ($i == 1) {
                            $idnew = '00000000'.$id;
                        } elseif ($i == 2) {
                            $idnew = '0000000'.$id;
                        } elseif ($i == 3) {
                            $idnew = '000000'.$id;
                        } elseif ($i == 4) {
                            $idnew = '00000'.$id;
                        } elseif ($i == 5) {
                            $idnew = '0000'.$id;
                        } elseif ($i == 6) {
                            $idnew = '000'.$id;
                        } elseif ($i == 7) {
                            $idnew = '00'.$id;
                        } elseif ($i == 8) {
                            $idnew = '0'.$id;
                        } elseif ($i == 9) {
                            $idnew = $id;
                        } else{
                            $idnew = 'null';
                        }
                    $filename3 = $row->B311_alpha_code.'_VISTEON_210_'.date('Ymd', strtotime($row->B309_dalivery_date)).'_'.$idnew;
                    $file3 = Storage::disk('localftp')->put('/'.$filename3.'.txt', "ISA*00*          *00*          *02*".$row->B311_alpha_code."           *01*VISTEON        *".date('ymd', strtotime($row->B309_dalivery_date))."*1138*U*00401*".$idnew."*0*T*^~GS*IM*".$row->B311_alpha_code."*VISTEON*".date('Ymd', strtotime($row->B309_dalivery_date))."*1138*".$id."*X*004010~ST*210*0001~B3**".$row->B302_invoice."*".$row->B303_shipment_identification_number."*".$row->B304_shipment_method_of_payment."**".date('Ymd', strtotime($row->B306_date))."*".$row->B307_net_amount_due."**".date('Ymd', strtotime($row->B309_dalivery_date))."*".$row->B310_datetime_qualifier."*".$row->B311_alpha_code."~C3*".$row->C301_currency_code."*".$row->C302_exchange_rate."~N9*".$row->N901_1_reference_identification_qualifier."*".$row->N902_1_reference_identification."*".$row->N903_1_description."~N9*".$row->N901_2_reference_identification_qualifier."*".$row->N902_2_reference_identification."~N9*".$row->N901_3_reference_identification_qualifier."*".$row->N902_3_reference_identification."~N9*".$row->N901_4_reference_identification_qualifier."*".$row->N902_4_reference_identification."*".$row->N903_4_description."~G62*".$row->G6201_1_date_qualifier."*".date('Ymd', strtotime($row->G6202_1_date))."~G62*".$row->G6201_2_date_qualifier."*".date('Ymd', strtotime($row->G6202_2_date))."~K1*".$row->K101_message."*".date('Ymd', strtotime($row->K102_message))."~N1*".$row->N101_1_identifier_code."*".$row->N102_1_name."~N3*".$row->N301_1_address."~N4*".$row->N401_1_city."*".$row->N402_1_state."*".$row->N403_1_postal_code."*".$row->N404_1_country."~N1*".$row->N101_2_identifier_code."*".$row->N102_2_name."~N3*".$row->N301_2_address."~N4*".$row->N401_2_city."*".$row->N402_2_state."*".$row->N403_2_postal_code."*".$row->N404_2_country."~N1*".$row->N101_3_identifier_code."*".$row->N102_3_name."~N3*".$row->N301_3_address."~N4*".$row->N401_3_city."*".$row->N402_3_state."*".$row->N403_3_postal_code."*".$row->N404_3_country."~N7**".$row->N702_equipment_number."*********".$row->N711_equipment_description_code."~LX*".$row->LX_2_assigned_number."~L5*".$row->L501_2_item_number."*".$row->L502_2_lading_description."~L1*".$row->L101_2_item_number."***".$row->L104_2_charge."****".$row->L108_2_special_charge."~L3*****".$row->L305_charge."~SE*25*0001~GE*1*".$id."~IEA*1*".$idnew."~");
                    //validaciones
                        if (empty($file3)) {
                            Log::error('Hubo fallos al crear archivo txt');
                        } else {
                            Log::info('Archivo creado con exito!');
                            $id = $row->B302_invoice;
                            $fecha = date('d/m/Y', strtotime($row->B309_dalivery_date));
                            $email = env('MAIL_SEND_VISTEON');
                            $ccmails = env('CCMAIL_SEND_VISTEON');
                            $cc = explode(',', $ccmails);
                            Mail::to($email)->cc([$cc[0],$cc[1]])->send(new NotificaVisteon($id, $fecha));
                            Log::info('Correo enviado!!');
                            //Actualizar Send_txt Valor a 0
                            $update = DB::connection('sqlsrvpro')->table("edi_210")->where('id_incremental',$row->id_incremental)->update(['send_txt' => '0']);
                                if (empty($update)) {
                                    Log::error('Error al Actualizar tabla edi_210 con Send_txt a 0');
                                } else {
                                    Log::info('tabla edi_210 actualizada');
                                }
                        }
                }
                else{
                    Log::info('Existen valores desconocidos en Send_txt');
                }
            }//for
        }
    }
}
