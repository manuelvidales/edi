<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\edidaimler;

class EdiDaimlerFtp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edi:daimlerFtp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'File download from ftp client Daimler';

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
        //credenciales del FTP desde el env
        $ftp_server = env('FTP_HOST');
        $ftp_user = env('FTP_USERNAME');
        $ftp_pass = env('FTP_PASSWORD');
        //establecer una conexión o finalizarla
        $conn_id = ftp_connect($ftp_server) or die("No se pudo conectar a $ftp_server");
        $login = ftp_login($conn_id, $ftp_user, $ftp_pass);
        //validar conexion FTP
        if ( @$login ) {
        // Obtener los archivos del directorio ftp
            $files = ftp_nlist($conn_id, 'fromRyder');
            $cantidad = count($files);
            for($i=0; $i<$cantidad; $i++){
                $filename = substr($files[$i], 10); //cortar nombre de directorio fromRyder
                //validar formato archivos .txt y con codigo #204
                if ( substr($filename,-4)==".txt" and substr($filename, 0, 6) == "RYD204") {
                    //Validar si ya existe el archivo
                    $buscar = Edidaimler::where('filename', $filename)->first();
                    if (empty($buscar)) {
                        Log::info('Archivo nuevo: '.$filename);
                        // Se procede a descargar archivo
                        $local = 'storage/app/Daimler/fromRyder/'.$filename; //ruta para almacenar
                            if (ftp_get($conn_id, $local, 'fromRyder/'.$filename, FTP_BINARY)) {
                                Log::info('Descarga exitosa: '.$filename);
                                    //almacenar info en mysql
                                    $data = new EdiDaimler;
                                    $data->code = '204';
                                    $data->filename = $filename;
                                    $data->status = '1';
                                    if ($data->save()) {
                                        Log::info('Almacenado BD con exito: '.$filename);
                                    } else {
                                        Log::warning('Error al guardar en BD: '.$filename);
                                    }
                                    //elimina el archivo del directorio ftp
                                        if (ftp_delete($conn_id, 'fromRyder/'.$filename)) {
                                            Log::info('Eliminado con exito: '.$filename);
                                        } else {
                                            Log::warning('No se logro eliminar: '.$filename);
                                        }
                            } else {
                                Log::error('Descarga de archivo: '.$filename);
                            }
                        }
                    } elseif ( substr($filename,-4)==".txt" and substr($filename, 0, 6) == "RYD824") {
                        //Validar si ya existe el archivo
                        $buscar = Edidaimler::where('filename', $filename)->first();
                        if (empty($buscar)) {
                            Log::info('Archivo nuevo: '.$filename);
                            // Se procede a descargar archivo
                            $local = 'storage/app/Daimler/fromRyder/'.$filename; //ruta para almacenar
                                if (ftp_get($conn_id, $local, 'fromRyder/'.$filename, FTP_BINARY)) {
                                    Log::info('Descarga exitosa: '.$filename);
                                        //almacenar info en mysql
                                        $data = new EdiDaimler;
                                        $data->code = '824';
                                        $data->filename = $filename;
                                        $data->status = '1';
                                        if ($data->save()) {
                                            Log::info('Almacenado BD con exito: '.$filename);
                                        } else {
                                            Log::warning('Error al guardar en BD: '.$filename);
                                        }
                                        //elimina el archivo del directorio ftp
                                            if (ftp_delete($conn_id, 'fromRyder/'.$filename)) {
                                                Log::info('Eliminado con exito: '.$filename);
                                            } else {
                                                Log::warning('No se logro eliminar: '.$filename);
                                            }
                                } else {
                                Log::error('Descarga de archivo: '.$filename);
                                }
                            }
                        }
                    }
            } else { //if ftplogin
            Log::error('No hay conexion al FTP');
        }
        ftp_close($conn_id); // cerrar conexión ftp
    }
}
