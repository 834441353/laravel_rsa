<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/10
 * Time: 15:49
 */

namespace App\GatewayWorker;

use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\Log;

class Events
{

    public static function onWorkerStart($businessWorker)
    {
        echo "onWorkerStart\r\n";
    }

    public static function onConnect($client_id)
    {
        Gateway::sendToClient($client_id, json_encode(['type' => 'onConnect', 'client_id' => $client_id]));
        echo "onConnect\r\n";
    }

    public static function onWebSocketConnect($client_id, $data)
    {
        echo "onWebSocketConnect\r\n";
    }

    public static function onMessage($client_id, $message)
    {
        Gateway::sendToClient($client_id, json_encode(['type' => 'onMessage', 'client_id' => $client_id, 'name' => json_decode($message)->name]));

        echo "onMessage\r\n";
    }

    public static function onClose($client_id)
    {
        Log::info('Workerman close connection' . $client_id);
        echo "onClose\r\n";
    }

}