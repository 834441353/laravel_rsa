<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Workerman\Worker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GatewayWorkerServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workman {action} {--d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a Workerman server.';

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
        //
        global $argv;
        $action = $this->argument('action');

        $argv[0] = 'wk';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';   //必须是一个-，上面定义命令两个--，后台启动用两个--

        $this->start();
    }
    private function start()
    {

        $udp_worker = new Worker('udp://0.0.0.0:9090');
        $udp_worker->onMessage = function($connection, $data){
            Log::info(json_encode($connection));
            $savePath ='st01/' . '/' . md5(time() . rand(1000, 9999));
            $status = Storage::put($savePath, $data);

        };
        Worker::runAll();
    }

}
