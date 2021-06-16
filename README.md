### 一、概述

云播报统一接入服务，适用于thinkphp 5.0及以上版本。支持两种云播报设备（嘉联云喇叭：jialian、阿里云语音播报：alibaba）

可直接下载SDK，放入项目中，添加如下代码：
```
include_once './autoload.php';
```
或，通过composer引入：
```
composer require zobeen/cloudbroadcast
```

### 二、嘉联云喇叭使用方法

1、获取客户身份标识（customer）、签名key（key）、设备ID（device_id）

2、初始化客户端
```
$client = zobeen\cloudbroadcast\Client::init('jialian', [
    'customer' => '',
    'key' => '',
    'domain' => 'https://apicloudspeaker.xgd.com/pay/result',
]);
```

3、发布消息
```
$data = [
    'pay_type' => zobeen\cloudbroadcast\core\driver\jialian\JiaLianCloudBroadcast::PAY_TYPE['wx'],
    'device_id' => '',
    'order_amount' => 1, // 单位：分
    'create_time' => date('YmdHis'),
    'order_id' => 'o' . date('YmdHis'),
    'store_id' => 1,
];
$client->publishMessage($data);
```

### 三、阿里云语音播报使用方法

1、获取AccessKeyId、AccessKeySecret、ProductKey、DeviceName

2、初始化客户端
```
$client = zobeen\cloudbroadcast\Client::init('alibaba', [
    'access_key_id' => '',
    'access_key_secret' => '',
    'region_id' => 'cn-shanghai',
]);
```

3、发布消息
```
$data = [
    'product_key' => '',
    'device_name' => '',
    'message' => '测试微信收款0.01元',
];
$client->publishMessage($data);
```
