<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Illuminate\Support\Str;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    public function __construct(RequestInterface $request, HttpResponse $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if (! Str::startsWith($path, '/' . config('admin.route.prefix')) || $user = admin_user()) {
            return $handler->handle($request);
        }

        if (in_array($path, [admin_url('auth/login'), admin_url('auth/logout')])) {
            return $handler->handle($request);
        }

        if (empty($user)) {
            if ($path === admin_url('auth/lock')) {
                return $this->response->redirect(admin_url('auth/logout'));
            } elseif ($path !== admin_url()) {
                admin_toastr(trans('admin.invalid_token'), 'warning');
            }

            return $this->response->redirect(admin_url('auth/login'));
        }

        return $handler->handle($request);
    }
}
