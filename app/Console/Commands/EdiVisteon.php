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
        $filename = 'Edi210';
        //Crear archivo TxT 210 //CREA NUEVa Conexion FTP
        Storage::disk('public')->put('storage/'.$filename.'.txt', "ISA*00* PRUEBA*00*~");
        Log::info('Archivo almacenado!!');

        $today = date_create('now');

        $id = '123456789';
        $fecha = $today->format('d/m/Y');
        $email = env('MAIL_SEND_VISTEON');
        Mail::to($email)->send(new NotificaVisteon($id, $fecha));
        Log::info('Correo enviado!!');

    }
}
