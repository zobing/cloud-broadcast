<?php
/**
 * Desc: 嘉联云音箱驱动
 * Author: zobeen@163.com
 * Datetime: 2021-6-15 17:04:40
 */
namespace zobeen\cloudbroadcast\core\driver\jialian;

use zobeen\cloudbroadcast\core\CloudBroadcastInterface;
use zobeen\cloudbroadcast\core\exception\PublishException;

class JiaLianCloudBroadcast implements CloudBroadcastInterface
{
    // 支付方式
    const PAY_TYPE = [
        'auto'      => '0', // 自定义，客户指定类型
        'wx'        => '1', // 微信
        'qq'        => '2', // QQ
        'ali'       => '3', // 支付宝
        'union'     => '4', // 银联云闪付
        'jd'        => '5', // 京东
        'bd'        => '6', // 百度
        'mi'        => '7', // 小米钱包
        'huawei'    => '8', // 华为钱包
        'card'      => '9', // 银行卡
        'credit'    => '10', // 信用卡
        'apple'     => '11', // ApplePay
        'union1'    => '12', // 银联闪付
        'union2'    => '13', // 银联快捷支付
        'other'     => '14', // 其他
        'tts'       => '11000', // tts 模式
    ];

    /**
     * 客户标志
     * @var string
     */
    protected $customer;

    /**
     * 签名key
     * @var string
     */
    protected $key;

    /**
     * 域名
     * @var string
     */
    protected $domain;

    /**
     * JiaLianCloudBroadcast constructor.
     * @param string $domain 连接地址
     * @param string $customer 客户识别号
     * @param string $key 签名密钥
     */
    public function __construct($domain = '', $customer = '', $key = '')
    {
        $this->customer = $customer;
        $this->key = $key;
        $this->domain = $domain;
    }

    /**
     * 发布消息
     * @param array $params
     * @param bool $debug
     * @return mixed|void
     * author zobeen@163.com
     * datetime 2021-6-15 18:21:38
     */
    public function publishMessage($params = [], $debug = false)
    {
        empty($params['customer']) and $params['customer'] = $this->customer;

        // 签名
        $sign = $this->createSign($params, $this->key);

        $params['sign'] = $sign;

        $resp = $this->httpPost($this->domain, json_encode($params), $debug);
        $resp = json_decode($resp);
        if ($resp && $resp->return_code == '200') {
            return true;
        } else {
            throw new PublishException($resp->return_msg);
        }
    }

    /**
     * 生成签名
     * @param $params
     * @param $key
     * @return string
     * author zobeen@163.com
     * datetime 2021-6-16 10:21:20
     */
    protected function createSign($params, $key)
    {

        $signPars = '';
        ksort($params);
        foreach ($params as $k => $v) {
            if (!empty($v) && 'sign' != $k) {
                $signPars .= $k . '=' . $v . '&';
            }
        }
        $signPars .= 'key=' . $key;
        return strtolower(md5($signPars));

    }

    /**
     * 发起post请示
     * @param $url
     * @param $data
     * @param bool $debug
     * @return bool|string
     * author zobeen@163.com
     * datetime 2021-6-16 10:39:39
     */
    protected function httpPost($url, $data, $debug = false)
    {

        $ch = curl_init($url);
        if (0 === strpos(strtolower($url), 'https')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //从证书中检查SSL加密算法是否存在
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 设置超时,cURL允许执行的最长秒数
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length:' . strlen($data),
            'Cache-Control: no-cache',
            'Pragma: no-cache'
        ));
        $response = curl_exec($ch);
        $requestinfo = curl_getinfo($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($data);
            echo "=====info===== \r\n";
            print_r($requestinfo);
            echo "=====response=====\r\n";
            print_r($response);
        }

        return $response;
    }
}