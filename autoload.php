<?php
/**
 * 类自动加载
 * @param string $class 类名
 * author zobeen@163.com
 * datetime 2021-6-16 12:00:37
 */
function classLoader($class = '')
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $path = str_replace('zobeen\cloudbroadcast', '', $path);

    $file = __DIR__ . '/src/' . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

spl_autoload_register('classLoader');
