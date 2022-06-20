<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Common;

use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;

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