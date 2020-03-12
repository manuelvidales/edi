<?php

namespace App\Http\Controllers;

use App\ediemerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\messagesend;

class EdiemersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

/** Separar por * cada Fila del array */

    public function path()
    {
        $name= 'ATIH_ELNEMRLOGP_204_4010_20151002190326052.txt';
        $dir = 'app/public/'.$name;
        $path = file::get(storage_path($dir));
        //echo $path;
        //dd($path);
        $array = explode("~", $path);

    echo '
<div class="container p-3">
    <div class="row">
        <div class="col-sm-6 text-danger">
        <label> <strong>Archivo:// '.$name.'<br>Codigo: 204</strong></label>
        </div>
    </div>
<div class="row justify-content-center">
        <div class="col-sm-6">
';
        //separacion por row requerido
        $row0 = $array[0];
        $tr0 = explode ("*", $row0);
            $row0td0= $tr0[0];
            $row0td5= $tr0[5];
            $row0td6= $tr0[6];
            $row0td7= $tr0[7];
            $row0td8= $tr0[8];
            $row0td11= $tr0[11];
            $row0td12= $tr0[12];
                echo '
                <table class="table table-sm table-striped">
                <thead><tr><th>0 - '.$row0td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row0td5.'</td></tr>
                <tr><td>'.$row0td6.'</td></tr>
                <tr><td>'.$row0td7.'</td></tr>
                <tr><td>'.$row0td8.'</td></tr>
                <tr><td>'.$row0td11.'</td></tr>
                <tr><td>'.$row0td12.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row1 = $array[1];
        $tr1 = explode ("*", $row1);
            $row1td0= $tr1[0];
            $row1td2= $tr1[2];
            $row1td7= $tr1[7];
            $row1td8= $tr1[8];
                echo '
                <table class="table table-sm table-striped center">
                <thead><tr><th> 1 - '.$row1td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row1td2.'</td></tr>
                <tr><td>'.$row1td7.'</td></tr>
                <tr><td>'.$row1td8.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row3 = $array[3];
        $tr3 = explode ("*", $row3);
            $row3td0= $tr3[0];
            $row3td2= $tr3[2];
            $row3td4= $tr3[4];
            $row3td6= $tr3[6];
                echo '
                <table class="table table-sm table-striped center">
                <thead><tr><th>3 - '.$row3td0.'</th></tr></thead>
                <tbody class="text-center">                
                <tr><td>'.$row3td2.'</td></tr>
                <tr><td>'.$row3td4.'</td></tr>
                <tr><td>'.$row3td6.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row6 = $array[6];
        $tr6 = explode ("*", $row6);
            $row6td0= $tr6[0];
            $row6td1= $tr6[1];
            $row6td2= $tr6[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th> 6 - '.$row6td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row6td1.'</td></tr>
                <tr><td>'.$row6td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row7 = $array[7];
        $tr7 = explode ("*", $row7);
            $row7td0= $tr7[0];
            $row7td1= $tr7[1];
            $row7td2= $tr7[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>7 - '.$row7td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row7td1.'</td></tr>
                <tr><td>'.$row7td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row15 = $array[15];
        $tr15 = explode ("*", $row15);
            $row15td0= $tr15[0];
            $row15td2= $tr15[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>15 - '.$row15td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row15td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row18 = $array[18];
        $tr18 = explode ("*", $row18);
            $row18td0= $tr18[0];
            $row18td2= $tr18[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>18 - '.$row18td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row18td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row19 = $array[19];
        $tr19 = explode ("*", $row19);
            $row19td0= $tr19[0];
            $row19td1= $tr19[1];
            $row19td2= $tr19[2];
            $row19td3= $tr19[3];
            $row19td4= $tr19[4];
            $row19td5= $tr19[5];
            $row19td6= $tr19[6];
            $row19td7= $tr19[7];
            $row19td8= $tr19[8];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>19 - '.$row19td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row19td1.'</td></tr>
                <tr><td>'.$row18td2.'</td></tr>
                <tr><td>'.$row19td3.'</td></tr>
                <tr><td>'.$row19td4.'</td></tr>
                <tr><td>'.$row19td5.'</td></tr>
                <tr><td>'.$row19td6.'</td></tr>
                <tr><td>'.$row19td7.'</td></tr>
                <tr><td>'.$row19td8.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row20 = $array[20];
        $tr20 = explode ("*", $row20);
            $row20td0= $tr20[0];
            $row20td1= $tr20[1];
            $row20td2= $tr20[2];
            $row20td3= $tr20[3];
            $row20td4= $tr20[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>20 - '.$row20td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row20td1.'</td></tr>
                <tr><td>'.$row20td2.'</td></tr>
                <tr><td>'.$row20td3.'</td></tr>
                <tr><td>'.$row20td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row21 = $array[21];
        $tr21 = explode ("*", $row21);
            $row21td0= $tr21[0];
            $row21td1= $tr21[1];
            $row21td2= $tr21[2];
            $row21td3= $tr21[3];
            $row21td4= $tr21[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>21 - '.$row21td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row21td1.'</td></tr>
                <tr><td>'.$row21td2.'</td></tr>
                <tr><td>'.$row21td3.'</td></tr>
                <tr><td>'.$row21td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row22 = $array[22];
        $tr22 = explode ("*", $row22);
            $row22td0= $tr22[0];
            $row22td2= $tr22[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>22 - '.$row22td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row22td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row23 = $array[23];
        $tr23 = explode ("*", $row23);
            $row23td0= $tr23[0];
            $row23td1= $tr23[1];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>23 - '.$row23td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row23td1.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row24 = $array[24];
        $tr24 = explode ("*", $row24);
            $row24td0= $tr24[0];
            $row24td1= $tr24[1];
            $row24td2= $tr24[2];
            $row24td3= $tr24[3];
            $row24td4= $tr24[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>24 - '.$row24td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row24td1.'</td></tr>
                <tr><td>'.$row24td2.'</td></tr>
                <tr><td>'.$row24td3.'</td></tr>
                <tr><td>'.$row24td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row25 = $array[25];
        $tr25 = explode ("*", $row25);
            $row25td0= $tr25[0];
            $row25td4= $tr25[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>25 - '.$row25td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row25td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row26 = $array[26];
        $tr26 = explode ("*", $row26);
            $row26td0= $tr26[0];
            $row26td1= $tr26[1];
            $row26td2= $tr26[2];
            $row26td3= $tr26[3];
            $row26td4= $tr26[4];
            $row26td5= $tr26[5];
            $row26td6= $tr26[6];
            $row26td7= $tr26[7];
            $row26td8= $tr26[8];
            $row26td9= $tr26[9];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>26 - '.$row26td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row26td1.'</td></tr>
                <tr><td>'.$row26td2.'</td></tr>
                <tr><td>'.$row26td3.'</td></tr>
                <tr><td>'.$row26td4.'</td></tr>
                <tr><td>'.$row26td5.'</td></tr>
                <tr><td>'.$row26td6.'</td></tr>
                <tr><td>'.$row26td7.'</td></tr>
                <tr><td>'.$row26td8.'</td></tr>
                <tr><td>'.$row26td9.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row30 = $array[30];
        $tr30 = explode ("*", $row30);
            $row30td0= $tr30[0];
            $row30td1= $tr30[1];
            $row30td2= $tr30[2];
            $row30td3= $tr30[3];
            $row30td4= $tr30[4];
            $row30td5= $tr30[5];
            $row30td6= $tr30[6];
            $row30td7= $tr30[7];
            $row30td8= $tr30[8];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>30 - '.$row30td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row30td1.'</td></tr>
                <tr><td>'.$row30td2.'</td></tr>
                <tr><td>'.$row30td3.'</td></tr>
                <tr><td>'.$row30td4.'</td></tr>
                <tr><td>'.$row30td5.'</td></tr>
                <tr><td>'.$row30td6.'</td></tr>
                <tr><td>'.$row30td7.'</td></tr>
                <tr><td>'.$row30td8.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row31 = $array[31];
        $tr31 = explode ("*", $row31);
            $row31td0= $tr31[0];
            $row31td1= $tr31[1];
            $row31td2= $tr31[2];
            $row31td3= $tr31[3];
            $row31td4= $tr31[4];
            $row31td5= $tr31[5];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>31 - '.$row31td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row31td1.'</td></tr>
                <tr><td>'.$row31td2.'</td></tr>
                <tr><td>'.$row31td3.'</td></tr>
                <tr><td>'.$row31td4.'</td></tr>
                <tr><td>'.$row31td5.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row32 = $array[32];
        $tr32 = explode ("*", $row32);
            $row32td0= $tr32[0];
            $row32td2= $tr32[2];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>32 - '.$row32td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row32td2.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row33 = $array[33];
        $tr33 = explode ("*", $row33);
            $row33td0= $tr33[0];
            $row33td1= $tr33[1];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>33 - '.$row33td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row33td1.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row34 = $array[34];
        $tr34 = explode ("*", $row34);
            $row34td0= $tr34[0];
            $row34td1= $tr34[1];
            $row34td2= $tr34[2];
            $row34td3= $tr34[3];
            $row34td4= $tr34[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>34 - '.$row34td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row34td1.'</td></tr>
                <tr><td>'.$row34td2.'</td></tr>
                <tr><td>'.$row34td3.'</td></tr>
                <tr><td>'.$row34td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        $row36 = $array[36];
        $tr36 = explode ("*", $row36);
            $row36td0= $tr36[0];
            $row36td4= $tr36[4];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>36 - '.$row36td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row36td4.'</td></tr>
                </tbody></table>';
                echo "<hr>";
        
        $row37 = $array[37];
        $tr37 = explode ("*", $row37);
            $row37td0= $tr37[0];
            $row37td1= $tr37[1];
            $row37td2= $tr37[2];
            
            $row37td4= $tr37[4];
            $row37td5= $tr37[5];
            $row37td6= $tr37[6];
            $row37td7= $tr37[7];
            $row37td8= $tr37[8];
            $row37td9= $tr37[9];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>37 - '.$row37td0.'</th></tr></thead>
                <tbody class="text-center">
                <tr><td>'.$row37td1.'</td></tr>
                <tr><td>'.$row37td2.'</td></tr>
                
                <tr><td>'.$row37td4.'</td></tr>
                <tr><td>'.$row37td5.'</td></tr>
                <tr><td>'.$row37td6.'</td></tr>
                <tr><td>'.$row37td7.'</td></tr>
                <tr><td>'.$row37td8.'</td></tr>
                <tr><td>'.$row37td9.'</td></tr>
                </tbody></table>';
                echo "<hr>";

        echo '</div><div class="col-sm-5"></div></div>';

        return \view('vista');
    
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name= 'ATIH_ELNEMRLOGP_204_4010_20151002190326052.txt';
        $dir = 'app/public/'.$name;
        $path = file::get(storage_path($dir));
        $array = explode("~", $path);

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
            
        $row3 = $array[3];
        $tr3 = explode ("*", $row3);
            $row3td2= $tr3[2];
            $row3td4= $tr3[4];
            $row3td6= $tr3[6];

        $row6 = $array[6];
        $tr6 = explode ("*", $row6);
            $row6td1= $tr6[1];
            $row6td2= $tr6[2];

        $row7 = $array[7];
        $tr7 = explode ("*", $row7);
            $row7td1= $tr7[1];
            $row7td2= $tr7[2];

        $row15 = $array[15];
        $tr15 = explode ("*", $row15);
            $row15td2= $tr15[2];

        $row18 = $array[18];
        $tr18 = explode ("*", $row18);
            $row18td2= $tr18[2];

        $row19 = $array[19];
        $tr19 = explode ("*", $row19);
            $row19td1= $tr19[1];
            $row19td2= $tr19[2];
            $row19td3= $tr19[3];
            $row19td4= $tr19[4];
            $row19td5= $tr19[5];
            $row19td6= $tr19[6];
            $row19td7= $tr19[7];
            $row19td8= $tr19[8];

        $row20 = $array[20];
        $tr20 = explode ("*", $row20);
            $row20td1= $tr20[1];
            $row20td2= $tr20[2];
            $row20td3= $tr20[3];
            $row20td4= $tr20[4];

        $row21 = $array[21];
        $tr21 = explode ("*", $row21);
            $row21td1= $tr21[1];
            $row21td2= $tr21[2];
            $row21td3= $tr21[3];
            $row21td4= $tr21[4];

        $row22 = $array[22];
        $tr22 = explode ("*", $row22);
            $row22td2= $tr22[2];

        $row23 = $array[23];
        $tr23 = explode ("*", $row23);
            $row23td1= $tr23[1];

        $row24 = $array[24];
        $tr24 = explode ("*", $row24);
            $row24td1= $tr24[1];
            $row24td2= $tr24[2];
            $row24td3= $tr24[3];
            $row24td4= $tr24[4];

        $row26 = $array[26];
        $tr26 = explode ("*", $row26);
            $row26td1= $tr26[1];
            $row26td2= $tr26[2];
            $row26td4= $tr26[4];
            $row26td5= $tr26[5];
            $row26td6= $tr26[6];
            $row26td7= $tr26[7];
            $row26td8= $tr26[8];
            $row26td9= $tr26[9];

        $row30 = $array[30];
        $tr30 = explode ("*", $row30);
            $row30td1= $tr30[1];
            $row30td2= $tr30[2];
            $row30td3= $tr30[3];
            $row30td4= $tr30[4];
            $row30td5= $tr30[5];
            $row30td6= $tr30[6];
            $row30td7= $tr30[7];
            $row30td8= $tr30[8];

        $row31 = $array[31];
        $tr31 = explode ("*", $row31);
            $row31td1= $tr31[1];
            $row31td2= $tr31[2];
            $row31td3= $tr31[3];
            $row31td4= $tr31[4];
            $row31td5= $tr31[5];

        $row32 = $array[32];
        $tr32 = explode ("*", $row32);
            $row32td2= $tr32[2];

        $row33 = $array[33];
        $tr33 = explode ("*", $row33);
            $row33td1= $tr33[1];

        $row34 = $array[34];
        $tr34 = explode ("*", $row34);
            $row34td1= $tr34[1];
            $row34td2= $tr34[2];
            $row34td3= $tr34[3];
            $row34td4= $tr34[4];

        $row37 = $array[37];
        $tr37 = explode ("*", $row37);
            $row37td1= $tr37[1];
            $row37td2= $tr37[2];
            $row37td4= $tr37[4];
            $row37td5= $tr37[5];
            $row37td6= $tr37[6];
            $row37td7= $tr37[7];
            $row37td8= $tr37[8];
            $row37td9= $tr37[9];


        $save = \DB::connection('sqlsrv')->table("edi_emerson")->insert([
            'id_qualifier_sender' => $row0td5,
            'id_sender' => $row0td6,
            'id_qualifier_receiver' => $row0td7,
            'id_receiver' => $row0td8,
            'version_number' => $row0td11,
            'control_number' => $row0td12,
            'sender_code' => $row1td2,
            'agency_code' => $row1td7,
            'industry_identifier' => $row1td8,
            'alpha_code' => $row3td2,
            'shipment_identification_number' => $row3td4,
            'method_payment' => $row3td6,
            'reference_identification' => $row6td1,
            'reference_identification_qualifier' => $row6td2,
            'trailer' => $row7td1,
            'reference_identification_qualifier_shipper' => $row7td2,
            'client' => $row15td2,
            'equipment_number' => $row18td2,
            'stop_number_load' => $row19td1,
            'stop_reason_code_load' => $row19td2,
            'weight_load' => $row19td3,
            'weight_units_load' => $row19td4,
            'quantity_load' => $row19td5,
            'unit_for_measurement_load' => $row19td6,
            'volume_load' => $row19td7,
            'volume_unit_qualifier_load' => $row19td8,
            'load_date_qualifier_1' => $row20td1,
            'load_date_1' => $row20td2,
            'load_time_qualifier_1' => $row20td3,
            'load_time_1' => $row20td4,
            'load_date_qualifier_2' => $row21td1,
            'load_date_2' => $row21td2,
            'load_time_qualifier_2' => $row21td3,
            'load_time_2' => $row21td4,
            'origin' => $row22td2,
            'addres_origin' => $row23td1,            
            'city_origin' => $row24td1,
            'state_origin' => $row24td2,
            'postal_code_origin' => $row24td3,
            'country_origin' => $row24td4,
            'oid_reference_load' => $row26td1,
            'oid_purchase_load' => $row26td2,
            'oid_unit_for_measurement_load' => $row26td4,
            'oid_quantity_load' => $row26td5,
            'oid_weight_unit_code_load' => $row26td6,
            'oid_weight_load' => $row26td7,
            'oid_volume_unit_qualifier_load' => $row26td8,
            'oid_volume_load' => $row26td9,
            'stop_number_stop1' => $row30td1,
            'stop_reason_code_stop1' => $row30td2,
            'weight_stop1' => $row30td3,
            'weight_units_stop1' => $row30td4,
            'quantity_stop1' => $row30td5,
            'unit_for_measurement_stop1' => $row30td6,
            'volume_stop1' => $row30td7,
            'volume_unit_qualifier_stop1' => $row30td8,
            'stop1_date_qualifier' => $row31td1,
            'stop1_date' => $row31td2,
            'stop1_time_qualifier' => $row31td3,
            'stop1_time' => $row31td4,
            'stop1_time_code' => $row31td5,
            'stop1' => $row32td2,
            'addres_stop1' => $row33td1,
            'city_stop1' => $row34td1,
            'state_stop1' => $row34td2,
            'postal_code_stop1' => $row34td3,
            'country_stop1' => $row34td4,
            'oid_reference_stop1' => $row37td1,
            'oid_purchase_stop1' => $row37td2,
            'oid_unit_for_measurement_stop1' => $row37td4,
            'oid_quantity_stop1' => $row37td5,
            'oid_weight_unit_code_stop1' => $row37td6,
            'oid_weight_stop1' => $row37td7,
            'oid_volume_unit_qualifier_stop1' => $row37td8,
            'oid_volume_stop1' => $row37td9,
        ]);
 

        return back()->with('msg', 'Insertados con exito');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ediemerson  $ediemerson
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $categoria = \DB::connection('sqlsrv')->table("edi_emerson")->get();

        dd($categoria);

/*
        $datos = new ediemerson();
        
        $datos->id_qualifier_sender = $row0td5;
        $datos->id_sender = $row0td6;
        $datos->id_qualifier_receiver = $row0td7;
        $datos->id_receiver = $row0td8;
        $datos->version_number = $row0td11;
        $datos->control_number = $row0td12;

        $datos->sender_code = $row1td2;
        $datos->agency_code = $row1td7;
        $datos->industry_identifier = $row1td8;
        
        $datos->alpha_code = $row3td2;
        $datos->shipment_identification_number = $row3td4;
        $datos->method_payment = $row3td6;
        
        $datos->reference_identification = $row6td1;
        $datos->reference_identification_qualifier = $row6td2;
        
        $datos->trailer = $row7td1;
        $datos->reference_identification_qualifier_shipper = $row7td2;
        
        $datos->client = $row15td2;
        
        $datos->equipment_number = $row18td2;
        
        $datos->stop_number_load = $row19td1;
        $datos->stop_reason_code_load = $row19td2;
        $datos->weight_load = $row19td3;
        $datos->weight_units_load = $row19td4;
        $datos->quantity_load = $row19td5;
        $datos->unit_for_measurement_load = $row19td6;
        $datos->volume_load = $row19td7;
        $datos->volume_unit_qualifier_load = $row19td8;
        
        $datos->load_date_qualifier_1 = $row20td1;
        $datos->load_date_1 = $row20td2;
        $datos->load_time_qualifier_1 = $row20td3;
        $datos->load_time_1 = $row20td4;
        
        $datos->load_date_qualifier_2 = $row21td1;
        $datos->load_date_2 = $row21td2;
        $datos->load_time_qualifier_2 = $row21td3;
        $datos->load_time_2 = $row21td4;

        $datos->origin = $row22td2;
        
        $datos->addres_origin = $row23td1;
        
        $datos->city_origin = $row24td1;
        $datos->state_origin = $row24td2;
        $datos->postal_code_origin = $row24td3;
        $datos->country_origin = $row24td4;

        $datos->oid_reference_load = $row26td1;        
        $datos->oid_purchase_load = $row26td2;
        $datos->oid_unit_for_measurement_load = $row26td4;
        $datos->oid_quantity_load = $row26td5;
        $datos->oid_weight_unit_code_load = $row26td6;
        $datos->oid_weight_load = $row26td7;
        $datos->oid_volume_unit_qualifier_load = $row26td8;
        $datos->oid_volume_load = $row26td9;

        $datos->stop_number_stop1 = $row30td1;
        $datos->stop_reason_code_stop1 = $row30td2;
        $datos->weight_stop1 = $row30td3;
        $datos->weight_units_stop1 = $row30td4;
        $datos->quantity_stop1 = $row30td5;
        $datos->unit_for_measurement_stop1 = $row30td6;
        $datos->volume_stop1 = $row30td7;
        $datos->volume_unit_qualifier_stop1 = $row30td8;

        $datos->stop1_date_qualifier = $row31td1;
        $datos->stop1_date = $row31td2;
        $datos->stop1_time_qualifier = $row31td3;
        $datos->stop1_time = $row31td4;
        $datos->stop1_time_code = $row31td5;

        $datos->stop1 = $row32td2;

        $datos->addres_stop1 = $row33td1;

        $datos->city_stop1 = $row34td1;
        $datos->state_stop1 = $row34td2;
        $datos->postal_code_stop1 = $row34td3;
        $datos->country_stop1 = $row34td4;

        $datos->oid_reference_stop1 = $row37td1;
        $datos->oid_purchase_stop1 = $row37td2;
        $datos->oid_unit_for_measurement_stop1 = $row37td4;
        $datos->oid_quantity_stop1 = $row37td5;
        $datos->oid_weight_unit_code_stop1 = $row37td6;
        $datos->oid_weight_stop1 = $row37td7;
        $datos->oid_volume_unit_qualifier_stop1 = $row37td8;
        $datos->oid_volume_stop1 = $row37td9;

        if ($datos->save()){
            return back()->with('msg', 'Insertados con exito');
        }
        else {
            return back()->with('err', 'Fallo al insertar');
        }
*/


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ediemerson  $ediemerson
     * @return \Illuminate\Http\Response
     */
    public function edit(ediemerson $ediemerson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ediemerson  $ediemerson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ediemerson $ediemerson)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ediemerson  $ediemerson
     * @return \Illuminate\Http\Response
     */
    public function destroy(ediemerson $ediemerson)
    {
        //
    }
}
