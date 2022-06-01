<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Oyhdd\Admin;

use Swoole\Constant;
use Hyperf\Server\Event;
use Hyperf\Framework\Bootstrap\TaskCallback;
use Hyperf\Framework\Bootstrap\FinishCallback;
use Oyhdd\Admin\Command\InstallCommand;
use Oyhdd\Admin\Exception\AdminExceptionHandler;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            // 合并到 config/autoload/commands.php 文件
            'commands' => [
                InstallCommand::class,
            ],
            // 合并到 config/autoload/listeners.php 文件
            'listeners' => [
            ],
            // 合并到 config/autoload/server.php 文件
            'server' => [
                'settings' => [
                    // hyperf/task所需相关配置
                    Constant::OPTION_TASK_WORKER_NUM => 8,
                    Constant::OPTION_TASK_ENABLE_COROUTINE => false,

                    // 静态资源
                    Constant::OPTION_DOCUMENT_ROOT => BASE_PATH . '/public',
                    Constant::OPTION_STATIC_HANDLER_LOCATIONS => [],
                    Constant::OPTION_ENABLE_STATIC_HANDLER => true,
                ],
                'callbacks' => [
                    // hyperf/task所需相关配置
                    Event::ON_TASK => [TaskCallback::class, 'onTask'],
                    Event::ON_FINISH => [FinishCallback::class, 'onFinish'],
                ],
            ],
            // 合并到 config/autoload/exceptions.php 文件
            'exceptions' => [
                'handler' => [
                    'http' => [
                        AdminExceptionHandler::class,
                    ],
                ],
            ],
            // 合并到 config/autoload/annotations.php 文件
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            // 发布资源文件
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'Publish configuration files',
                    'source' => dirname(__DIR__) . '/publish/admin.php',
                    'destination' => BASE_PATH . '/config/autoload/admin.php',
                ],
            ],
        ];
    }
}
