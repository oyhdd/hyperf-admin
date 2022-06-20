<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\Str;

class LockMiddleware implements MiddlewareInterface
{
    /**
     * @var HttpResponse
     */
    protected $response;

    public function __construct(HttpResponse $response)
    {
        $this->response  = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if (! Str::startsWith($path, '/' . config('admin.route.prefix'))) {
            return $handler->handle($request);
        }

        if (in_array($path, [admin_url('auth/lock'), admin_url('auth/unlock'), admin_url('auth/unlock')])) {
            return $handler->handle($request);
        }

        if (!empty(admin_user()->lock)) {
            return $this->response->redirect(admin_url('auth/lock'));
        }

        return $handler->handle($request);
    }
}
