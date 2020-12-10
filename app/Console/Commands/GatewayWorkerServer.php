<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Workerman\Worker;

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
//        $this->startGateWay();
//        $this->startBusinessWorker();
//        $this->startRegister();
        $udp_worker = new Worker('udp://127.0.0.1:9090');
        $udp_worker->onMessage = function($connection, $data){
            echo "onMessage\r\n";
        };
        Worker::runAll();
    }

    private function startBusinessWorker()
    {
        $worker                  = new BusinessWorker();
        $worker->name            = 'BusinessWorker';
        $worker->count           = 1;
        $worker->registerAddress = '127.0.0.1:1236';
        $worker->eventHandler    = \App\GatewayWorker\Events::class;
    }

    private function startGateWay()
    {
        $gateway = new Gateway("websocket://0.0.0.0:2346");
        $gateway->name                 = 'Gateway';
        $gateway->count                = 1;
        $gateway->lanIp                = '127.0.0.1';
        $gateway->startPort            = 2300;
        $gateway->pingInterval         = 30;
        $gateway->pingNotResponseLimit = 0;
        $gateway->pingData             = '{"type":"ping"}';
        $gateway->registerAddress      = '127.0.0.1:1236';
    }

    private function startRegister()
    {
        new Register('text://0.0.0.0:1236');
    }

    //php artisan workman start --d  之后    打开浏览器F12 将内容复制到console里return就行
    /* ws = new WebSocket("ws://192.168.136.128:2346");
     ws.onopen = function() {
         ws . send('{"name":"one","user_id":"111"}');
         ws . send('{"name":"two","user_id":"222"}');
     };
     ws.onmessage = function(e) {
         console.log("收到服务端的消息：" + e.data);
     };
     ws.onclose = function(e) {
         console.log("服务已断开" );
     };*/


}
