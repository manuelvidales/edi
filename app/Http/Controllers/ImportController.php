<?php

namespace App\Http\Controllers;

use App\import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\messagesend;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = Storage::disk('sftp')->files(''); //muestra los archivos en array
        $cantidad = count($files); //contador de archivos en el directorio
        for($i=0; $i<$cantidad; $i++)
            {
            
            //validar Solo archivos TxT
            if ( substr($files[$i],-4)==".txt") {
                echo $files[$i].'<br>';
                //Validar si ya existe el archivo
                $buscar = DB::table('imports')->where('filename', $files[$i])->first();
            
            if (empty($buscar)) {
                //guardar el nombre del archivo
                DB::table('imports')->insert(['filename' => $files[$i], 'estatus' => 'process' ]);

            $file = Storage::disk('mysftp')->get($files[$i]); //lectura del archivo txt
            $array = explode("~", $file); //separacion por signo ~            

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
                    <table class="table table-sm table-striped text-center">
                    <thead><tr><th>0 - '.$row0td0.'</th><th>Valores</th></tr></thead>
                    <tbody class="text-center">
                    <tr><td>id_qualifier_sender</td><td>'.$row0td5.'</td></tr>
                    <tr><td>id_sender</td><td>'.$row0td6.'</td></tr>
                    <tr><td>id_qualifier_receiver</td><td>'.$row0td7.'</td></tr>
                    <tr><td>id_receiver</td><td>'.$row0td8.'</td></tr>
                    <tr><td>version_number</td><td>'.$row0td11.'</td></tr>
                    <tr><td>control_number</td><td>'.$row0td12.'</td></tr>
                    </tbody></table>';
                    echo "<hr>";

            } else {
                echo 'Ya existe';
            }
        


        }//if first
        
    }//for

       
return \view('vista');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //consulta tabla
        $name = 'ATIH_ELNEMRLOGP_204_4010_20151002190326052.txt';
        $datos = DB::table('imports')->where('filename', $name)->first();

        $datenow = date_create('now');
        $datetime = $datenow->format('YmdHisu');

        $email='sistemas01@autofleteshalcon.com';
        
        //dd($datos);
        $filename = 'ATIH_REQUEST_990_'.$datetime;
        //$st01 = $datos->st01;
        $st02 = $datos->st02;
        $b201 = $datos->b201;
        $b202 = $datos->b202;
        $b204 = $datos->b204;
        $b206 = $datos->b206;
        $b2a01 = $datos->b2a01;
        $l1101 = $datos->l1101;
        $l1102 = $datos->l1102;
        $l11_01 = $datos->l11_01;
        $l11_02 = $datos->l11_02;

        $file = fopen('storage/'.$filename.'.txt', "w");
        fwrite($file, "nuevo datos desde nuestra BD" . PHP_EOL);
        fwrite($file, "ST*990*".$st02."~"."B2*".$b201."*".$b202."*".$b204."*".$b206."~B2A*".$b2a01."~L11*".$l1101."*".$l1102."*ShipmentID~L11*".$l11_01."*".$l11_02."*Equipment Type~" . PHP_EOL);
        fclose($file);

        Mail::to($email)->send(new messagesend);

        Return ('Terminado!!');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $name= 'ATIH_ELNEMRLOGP_204_4010_20151002190326052.txt';
        $dir = 'app/public/'.$name;
        $path = file::get(storage_path($dir));
        $array = explode("~", $path);

        $row2 = $array[2];
        $tr2 = explode ("*", $row2);
            $row2td0= $tr2[0];//encabezado
            $row2td1= $tr2[1];
            $row2td2= $tr2[2];
        
        $row3 = $array[3];
        $tr3 = explode ("*", $row3);
            $row3td0= $tr3[0];//encabezado
            $row3td1= $tr3[1];
            $row3td2= $tr3[2];
            $row3td4= $tr3[4];
            $row3td6= $tr3[6];

        $row4 = $array[4];
        $tr4 = explode ("*", $row4);
            $row4td0= $tr4[0];//encabezado
            $row4td1= $tr4[1];
        
        $row6 = $array[6];
        $tr6 = explode ("*", $row6);
            $row6td0= $tr6[0];//encabezado
            $row6td1= $tr6[1];
            $row6td2= $tr6[2];

        $row7 = $array[7];
        $tr7 = explode ("*", $row7);
            $row7td0= $tr7[0];//encabezado
            $row7td1= $tr7[1];
            $row7td2= $tr7[2];

        $datos = new import();
        $datos->filename = $name;
        $datos->st01 = $row2td1;
        $datos->st02 = $row2td2;
        $datos->b201 = $row3td1;
        $datos->b202 = $row3td2;
        $datos->b204 = $row3td4;
        $datos->b206 = $row3td6;
        $datos->b2a01 = $row4td1;
        $datos->l1101 = $row6td1;
        $datos->l1102 = $row6td2;
        $datos->l11_01 = $row7td1;
        $datos->l11_02 = $row7td2;

        if ($datos->save()){
            return back()->with('msg', 'Insertados con exito');
        }
        else {
            return back()->with('err', 'Fallo al insertar');
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\import  $import
     * @return \Illuminate\Http\Response
     */

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

//Envio de email




    return \view('vista');

    }

    public function show(Request $request)
    {
        //carga del archivo al array
        $contenido = file_get_contents($request->file);
        //separacion de array
        $array = explode("~", $contenido);


    /* Para desmenuzar el archivo TXT*/
        $longitud = count($array);
        /* Separacion por ~ */
        for($i=0; $i<$longitud; $i++)
            {
            echo "[".$i."] ".$array[$i];
            echo "<br>";
            }


/*
echo '
<div class="container p-3">
        
    <div class="row justify-content-center">
        <div class="col-lg-10">
';

        //separacion por row requerido
        $row2 = $array[2];
        $tr2 = explode ("*", $row2);
                $row2td0= $tr2[0];
                $row2td1= $tr2[1];
                $row2td2= $tr2[2];
                    echo '
                    <table class="table table-sm table-striped center">
                    <thead><tr><th>'.$row2td0.'</th></tr></thead>
                    <tbody>
                    <tr><td>'.$row2td1.'</td></tr>
                    <tr><td>'.$row2td2.'</td></tr>
                    </tbody></table>';
                    echo "<hr>";

        $row3 = $array[3];
        $tr3 = explode ("*", $row3);
            $row3td0= $tr3[0];
            $row3td1= $tr3[1];
            $row3td2= $tr3[2];
            $row3td4= $tr3[4];
            $row3td6= $tr3[6];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>'.$row3td0.'</th></tr></thead>
                <tbody>
                <tr><td>'.$row3td1.'</td></tr>
                <tr><td>'.$row3td2.'</td></tr>
                <tr><td>'.$row3td4.'</td></tr>
                <tr><td>'.$row3td6.'</td></tr>
                </tbody></table>';
                echo "<hr>";
        $row4 = $array[4];
        $tr4 = explode ("*", $row4);
            $row4td0= $tr4[0];
            $row4td1= $tr4[1];
                echo '
                <table class="table table-sm table-striped center"><thead><tr><th>'.$row4td0.'</th></tr></thead>
                <tbody>
                <tr><td>'.$row4td1.'</td></tr>
                </tbody></table>';
                echo "<hr>";

echo '</div></div></div>';

    return \view('vista');

*/

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\import  $import
     * @return \Illuminate\Http\Response
     */
    public function edit(import $import)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\import  $import
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, import $import)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\import  $import
     * @return \Illuminate\Http\Response
     */
    public function destroy(import $import)
    {
        //
    }
}
