<?php
/**
 * Desc: 阿里云物联网平台-语音播报测试
 * Author: zobeen@163.com
 * Datetime: 2021-6-16 14:13:26
 */

include_once './autoload.php';
include_once './vendor/autoload.php';

$client = zobeen\cloudbroadcast\Client::init('alibaba', [
    'access_key_id' => '请补充',
    'access_key_secret' => '请补充',
    'region_id' => 'cn-shanghai',
]);

$data = [
    'product_key' => '请补充',
    'device_name' => '请补充',
    'message' => '测试微信收款0.01元',
];
$client->publishMessage($data);