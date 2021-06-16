<?php
/**
 * Desc: 嘉联云喇叭测试
 * Author: zobeen@163.com
 * Datetime: 2021-6-16 10:13:26
 */

include_once './autoload.php';

$client = zobeen\cloudbroadcast\Client::init('jialian', [
    'customer' => '请补充',
    'key' => '请补充',
    'domain' => 'https://apicloudspeaker.xgd.com/pay/result',
]);

$data = [
    'pay_type' => zobeen\cloudbroadcast\core\driver\jialian\JiaLianCloudBroadcast::PAY_TYPE['wx'],
    'device_id' => '请补充',
    'order_amount' => 1,
    'create_time' => date('YmdHis'),
    'order_id' => 'o' . date('YmdHis'),
    'store_id' => 1,
];
$client->publishMessage($data);