<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class loglaravel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:laravel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Almacenar archivo por fecha';

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
        $today = date_create('now');
        $datetime = $today->format('Ymd-His');
        $filename = 'laravel_'.$datetime.'.log';
        Storage::disk('log')->move('laravel.log', $filename);
        Log::info('backup de Log realizado: '.$filename);
    }
}
