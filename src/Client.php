<?php
/**
 * Desc: 云播报客户端
 * Author: zobeen@163.com
 * Datetime: 2021-6-15 11:31:00
 */
namespace zobeen\cloudbroadcast;

use zobeen\cloudbroadcast\core\CloudBroadcastInterface;
use zobeen\cloudbroadcast\core\driver\alibaba\AlibabaCloudBroadcast;
use zobeen\cloudbroadcast\core\driver\jialian\JiaLianCloudBroadcast;
use zobeen\cloudbroadcast\core\exception\DriverConfigException;
use zobeen\cloudbroadcast\core\exception\DriverException;

class Client
{
    /**
     * 云播报客户端初始化
     * @param string $driver 驱动名称
     * @param array $driverConfig 驱动配置
     * @return CloudBroadcastInterface
     * author zobeen@163.com
     * datetime 2021-6-15 17:28:38
     */
    public static function init($driver = '', $driverConfig = [])
    {
        $client = null;

        switch ($driver) {
            case 'jialian':
                if (empty($driverConfig['customer'])) {
                    throw new DriverConfigException('客户标志未配置');
                }

                if (empty($driverConfig['key'])) {
                    throw new DriverConfigException('签名Key未配置');
                }

                if (empty($driverConfig['domain'])) {
                    throw new DriverConfigException('连接地址未配置');
                }

                $client = new JiaLianCloudBroadcast($driverConfig['domain'], $driverConfig['customer'], $driverConfig['key']);
                break;
            case 'alibaba':
                if (empty($driverConfig['access_key_id'])) {
                    throw new DriverConfigException('AccessKeyID未配置');
                }

                if (empty($driverConfig['access_key_secret'])) {
                    throw new DriverConfigException('AccessKeySecret未配置');
                }

                if (empty($driverConfig['region_id'])) {
                    throw new DriverConfigException('地域未配置');
                }

                $client = new AlibabaCloudBroadcast($driverConfig['access_key_id'], $driverConfig['access_key_secret'], $driverConfig['region_id']);
                break;
            default:
                throw new DriverException('未适配该类型设备');
        }

        return $client;
    }
}