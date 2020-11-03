<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\clientes;

class SendClientesGps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Notificaciones:clientesGps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio datos Gps de viajes a clientes';

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
        $gps = DB::connection('sqlsrvpro')->table("bitacora_clientes_gps")->where('send_txt','=', '1')->get();
        $id = '2';
        if (count($gps) !== 0) {
            Log::info('Datos de viajes para gps');
            foreach ($gps as $key => $value) {
                $datagps = new clientes();
                $datagps->statusData($value, $id);
            }
        }
    }
}
