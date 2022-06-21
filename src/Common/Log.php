<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Common;

use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;

/**
 * Class Log.
 *
 * @method Monolog\Logger       debug($message, array $context = [])
 * @method Monolog\Logger       info($message, array $context = [])
 * @method Monolog\Logger       notice($message, array $context = [])
 * @method Monolog\Logger       warning($message, array $context = [])
 * @method Monolog\Logger       error($message, array $context = [])
 * @method Monolog\Logger       critical($message, array $context = [])
 * @method Monolog\Logger       alert($message, array $context = [])
 * @method Monolog\Logger       emergency($message, array $context = [])
 * ...
 */
class Log
{
    public static function get(string $name = 'app')
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->get($name);
    }

    public static function __callStatic(string $method, $arguments)
    {
        $log = self::get();

        return call_user_func_array([$log, $method], $arguments);
    }
}