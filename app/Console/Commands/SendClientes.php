<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\clientes;


class SendClientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Notificaciones:clientes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de status viaje a clientes';

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
        $viajes = DB::connection('sqlsrvpro')->table("bitacora_clientes")->where('send_txt','=', '1')->get();
        if (count($viajes) !== 0) {
            Log::info('viajes encontrados');
            foreach ($viajes as $key => $value) {
                $enviar = new clientes();
                $enviar->statusViajes($value);
            }
        }
    }
}
