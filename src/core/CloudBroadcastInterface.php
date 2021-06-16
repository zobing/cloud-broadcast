<?php
/**
 * Desc: *
 * Author: zobeen@163.com
 * Datetime: 2021-6-15 15:48:01
 */
namespace zobeen\cloudbroadcast\core;

interface CloudBroadcastInterface
{
    /**
     * 发布消息
     * @return mixed
     * author zobeen@163.com
     * datetime 2021-6-15 16:37:43
     */
    public function publishMessage();
}