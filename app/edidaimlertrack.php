<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class edidaimlertrack extends Model
{
    public function save214gps($data, $name214gps, $Longitud, $Latitud) {
        $datatrack = new edidaimlertrack();
        $datatrack->code = '214';
        $datatrack->filename = $name214gps.'.txt';
        $datatrack->id_incremental = $data->id_incremental;
        $datatrack->shipment_identification_number = $data->shipment_identification_number;
        $datatrack->alpha_code = $data->alpha_code;
        $datatrack->status_code = $data->status_code;
        $datatrack->reason_code = $data->reason_code;
        $datatrack->reference_identification = $data->reference_identification;
        $datatrack->longitude = $Longitud;
        $datatrack->code_longitude = $data->code_longitude;
        $datatrack->latitude = $Latitud;
        $datatrack->code_latitude = $data->code_latitude;
        $datatrack->unidad = $data->unidad;
        $datatrack->equipment = $data->equipment;
        $datatrack->id_qualifier_sender = $data->id_qualifier_sender;
        $datatrack->id_sender = $data->id_sender;
        $datatrack->id_qualifier_receiver = $data->id_qualifier_receiver;
        $datatrack->id_receiver = $data->id_receiver;
        $datatrack->version_number = $data->version_number;
        $datatrack->control_number = $data->control_number;
        $datatrack->sender_code = $data->sender_code;
        $datatrack->agency_code = $data->agency_code;
        $datatrack->industry_identifier = $data->industry_identifier;
        $datatrack->date_time = $data->date_time;
        if ($datatrack->save()) {
            Log::info('datos almacenados para 214_gps');
        } else {
            Log::error('Fallo almacenar datos 214_gps');
        }
    }
}
