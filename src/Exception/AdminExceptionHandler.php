<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Exception;

use Throwable;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;

class AdminExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $th, ResponseInterface $response)
    {
        $data = json_encode([
            'type'    => get_class($th),
            'message' => $th->getMessage(),
            'file'    => $th->getFile(),
            'line'    => $th->getLine(),
            'trace'   => $this->replaceBasePath($th->getTraceAsString()),
        ], JSON_UNESCAPED_UNICODE);

        admin_toastr($th->getMessage(), 'error');

        $this->stopPropagation();
        return $response->withStatus(200)->withBody(new SwooleStream($data));
    }

    public function isValid(Throwable $th): bool
    {
        return $th instanceof AdminException;
    }

    protected function replaceBasePath(string $path)
    {
        return str_replace(
            str_replace('\\', '/', BASE_PATH . '/'),
            '',
            str_replace('\\', '/', $path)
        );
    }
}
