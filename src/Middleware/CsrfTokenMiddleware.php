<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Middleware;

use Carbon\Carbon;
use Hyperf\HttpMessage\Cookie\Cookie;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\Str;
use HyperfExt\Hashing\Hash;


class CsrfTokenMiddleware implements MiddlewareInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var HttpResponse
     */
    protected $response;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [];

    public function __construct(RequestInterface $request, HttpResponse $response)
    {
        $this->request  = $request;
        $this->response = $response;

        $this->except = config('admin.csrf_token.except', []);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if (! Str::startsWith($path, '/' . config('admin.route.prefix'))) {
            return $handler->handle($request);
        }

        if ($this->isReading() || $this->inExceptArray($path) || $this->validToken()) {
            $response = $handler->handle($request);
            return $this->addCookieToResponse($response);
        }

        admin_toastr(trans('admin.invalid_csrf_token'), 'warning');
        return $this->response->redirect(admin_url('auth/login'));
    }

    
    /**
     * Determine if the HTTP request uses a ‘read’ verb.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isReading()
    {
        return in_array($this->request->getMethod(), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray(string $path)
    {
        $path = admin_url_without_prefix($path);
        if (is_uri($this->except, $path)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @return bool
     */
    protected function validToken(): bool
    {
        if ($token = $this->request->input('_token') ?: $this->request->header('X-CSRF-TOKEN')) {
            return hash_equals(csrf_token(), $token);
        }

        if ($token = $this->request->cookie('XSRF-TOKEN', '')) {
            return Hash::check(csrf_token(), $token);
        }

        return false;
    }

    /**
     * Add the CSRF token to the response cookies.
     */
    protected function addCookieToResponse(ResponseInterface $response): ResponseInterface
    {
        return $response->withCookie(
            new Cookie(
                'XSRF-TOKEN',
                Hash::make(csrf_token()),
                Carbon::now()->addSeconds(config('session.options.cookie_lifetime'))->getTimestamp(),
                // '/' . config('admin.route.prefix'),
                // config('session.options.domain') ?? $this->request->getUri()->getHost(),
                // strtolower($this->request->getUri()->getScheme()) === 'https',
                // true,
                // false,
                // Cookie::SAMESITE_STRICT
            )
        );
    }
}