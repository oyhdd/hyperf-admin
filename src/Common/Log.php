<?php

declare(strict_types=1);

namespace Oyhdd\Admin\Common;

use Hyperf\Logger\Logger;
use Hyperf\Utils\ApplicationContext;

class Log
{
    public static function get()
    {
        return ApplicationContext::getContainer()->get(\Hyperf\Logger\LoggerFactory::class)->get('admin', config('admin.logger', 'admin'));
    }

    public static function __callStatic(string $method, $arguments)
    {
        $log = self::get();
        return call_user_func_array([$log, $method], $arguments);
    }
}