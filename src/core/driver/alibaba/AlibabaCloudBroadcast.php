<?php
/**
 * Desc: 阿里巴巴云播报驱动
 * Doc: https://github.com/aliyun/openapi-sdk-php/blob/master/README-zh-CN.md
 *      https://help.aliyun.com/document_detail/30594.html?utm_content=g_1000230851&spm=5176.20966629.toubu.3.f2991ddcpxxvD1#title-5ko-hg6-k7q
 *      https://help.aliyun.com/document_detail/40654.htm?spm=a2c4g.11186623.2.21.7d6473c8vEyFlF#concept-2459516
 * Author: zobeen@163.com
 * Datetime: 2021-6-15 17:04:40
 */
namespace zobeen\cloudbroadcast\core\driver\alibaba;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use AlibabaCloud\Iot\Iot;
use zobeen\cloudbroadcast\core\CloudBroadcastInterface;
use zobeen\cloudbroadcast\core\exception\CloudBroadcastException;
use zobeen\cloudbroadcast\core\exception\PublishException;

class AlibabaCloudBroadcast implements CloudBroadcastInterface
{
    /**
     * AlibabaCloudBroadcast constructor.
     * @param string $accessKeyID
     * @param string $accessKeySecret
     * @param string $regionId 地域
     */
    public function __construct($accessKeyID = '', $accessKeySecret = '', $regionId = '')
    {
        // 设置一个全局客户端
        try {
            AlibabaCloud::accessKeyClient($accessKeyID, $accessKeySecret)->regionId($regionId)->asDefaultClient();
        } catch (ClientException $e) {
            throw new CloudBroadcastException($e->getErrorMessage());
        }
    }

    /**
     * 发布消息
     * @param array $params ['product_key' => 产品标识, 'device_name' => 产品名称, 'message' => 播报内容, 'cmd' => '', 'speed' => '', 'connect_timeout' => '', 'timeout' => '']
     * @return bool|mixed
     * author zobeen@163.com
     * datetime 2021-6-16 14:25:27
     */
    public function publishMessage($params = [])
    {
        try {
            // 业务数据
            $messageContent = [
                'cmd' => 'voice',
                'msg' => $params['message'],
                'speed' => 50,
                'msgid' => 'msg' . date('YmdHis') . rand(10000, 99999)
            ];
            empty($params['cmd']) or $messageContent['cmd'] = $params['cmd'];
            empty($params['speed']) or $messageContent['speed'] = $params['speed'];

            $topicFullName = "/{$params['product_key']}/{$params['device_name']}/user/get";

            $result = Iot::v20180120()
                ->Pub()
                ->withMessageContent(base64_encode(json_encode($messageContent, 256)))
                ->withProductKey($params['product_key'])
                ->withTopicFullName($topicFullName)
                ->connectTimeout($params['connect_timeout'] ?: 30) // 连接超时会抛出异常
                ->timeout($params['timeout'] ?: 60) // 超时会抛出异常
                ->request(); // 执行请求
            if ($result->Success) {
                return true;
            } else {
                throw new PublishException('播报失败');
            }
        } catch (ClientException $e) {
            throw new CloudBroadcastException($e->getErrorMessage());
        } catch (ServerException $e) {
            throw new CloudBroadcastException($e->getErrorMessage());
        }
    }
}