<?php declare (strict_types=1);

namespace Oyhdd\Admin\Task;

use Hyperf\Utils\Coroutine;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Task\Annotation\Task;
use Hyperf\Utils\Str;
use Oyhdd\Admin\Model\AdminOperationLog;

class AdminOperationLogTask
{
    /**
     * @Task
     */
    public function handle(int $user_id, array $serverParams, array $parsedParams)
    {
        try {
            $operationConfig = config('admin.operation_log');
            if (empty($operationConfig['enable'])) {
                return;
            }
            $method = $serverParams['request_method'];
            if (!in_array($method, $operationConfig['allowed_methods'])) {
                return;
            }
            $path = $serverParams['request_uri'];
            if (isset($serverParams['query_string'])) {
                $path .= "?".$serverParams['query_string'];
            }
            foreach ($operationConfig['except'] as $except) {
                if ($except !== '/') {
                    $except = trim($except, '/');
                }
                if ($this->is($except, trim($path, '/'))) {
                    return;
                }
            }
            (new AdminOperationLog())->fill([
                'user_id' => $user_id,
                'path'    => $path,
                'method'  => $method,
                'ip'      => $serverParams['remote_addr'],
                'input'   => json_encode($parsedParams, JSON_UNESCAPED_UNICODE),
            ])->save();
        } catch (\Throwable $e) {
        }
    }

    /**
     * Determine if the current request URI matches a pattern.
     *
     * @param mixed ...$patterns
     */
    public function is($pattern, $url): bool
    {
        if (Str::is($pattern, rawurldecode($url))) {
            return true;
        }

        return false;
    }
}