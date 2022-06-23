<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\Str;

class PermissionMiddleware implements MiddlewareInterface
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
        

        return $handler->handle($request);
    }
}
