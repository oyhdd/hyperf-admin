<?php declare(strict_types=1);

namespace Oyhdd\Admin\Middleware;

use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\ApplicationContext;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Contract\SessionInterface;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Phper666\JwtAuth\Jwt;
use Phper666\JwtAuth\Exception\TokenValidException;
use Oyhdd\Admin\Task\AdminOperationLogTask;
use Oyhdd\Admin\Model\AdminUser;
use Oyhdd\Admin\Common\Log;
use Oyhdd\Admin\Exception\AuthException;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var HttpResponse
     */
    protected $response;

    protected $prefix = 'Bearer';

    protected $jwt;

    protected $session;

    public function __construct(HttpResponse $response, Jwt $jwt, SessionInterface $session)
    {
        $this->response = $response;
        $this->jwt = $jwt;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $cookies = $request->getCookieParams();
            $token = $cookies['Authorization'] ?? '';
            $token = ucfirst($token);
            $arr = explode($this->prefix . ' ', $token);
            $token = $arr[1] ?? '';
            if (empty($token)) {
                throw new TokenValidException('登录态失效，请重新登录');
            }

            if (!$this->jwt->checkToken($token)) {
                throw new TokenValidException('登录态失效，请重新登录');
            }

            $tokenObj = $this->jwt->getTokenObj($token);
            $userId = $tokenObj->getClaim('id');
            $user = AdminUser::where('id', $userId)->where('status', AdminUser::STATUS_ENABLE)->first();
            if (empty($user)) {
                throw new TokenValidException('登录态失效，请重新登录');
            }

            $request = $request->withAttribute('user', $user);
            Context::set(ServerRequestInterface::class, $request);

            // 当前已锁屏锁屏
            $lock = $tokenObj->getClaim('lock', false);
            if ($lock) {
                return $this->response->redirect('/admin/user/lock');
            }

            // 记录操作日志
            $container = ApplicationContext::getContainer();
            $task = $container->get(AdminOperationLogTask::class);
            $task->handle($userId, $request->getServerParams(), $request->getParsedBody());

            // 检查权限
            if (!$this->checkPermission($request, $user)) {
                throw new AuthException(trans('admin.deny'), 403);
            }
            return $handler->handle($request);
        } catch (TokenValidException $e) {
            $this->session->forget('Authorization');
        } catch (\Throwable $t) {
            $error = sprintf('%s in %s:%s', $t->getMessage(), $t->getFile(), $t->getLine());
            Log::error("Server Error", [$error]);
            if ($request->isAjax()) {
                return $this->response->withStatus($t->getCode())->withBody(new SwooleStream($t->getMessage()));
            }
            $params = "code=".$t->getCode()."&error=".$t->getMessage();
            if ($request->getUri()->getPath() == '/admin/user/error' && rawurldecode($request->getUri()->getQuery()) == $params) {
                return $this->response->withStatus($t->getCode())->json(['error' => $t->getMessage()]);
            }

            return $this->response->redirect("/admin/user/error?".$params);
        }

        return $this->response->redirect('/admin/user/login');
    }

    /**
     * check the permission
     * @param  ServerRequestInterface  $request
     * @param  AdminUser               $user
     * @return bool
     */
    private function checkPermission(ServerRequestInterface $request, AdminUser $user): bool
    {
        $path = trim($request->getUri()->getPath(), '/');

        // 忽略以下路由权限
        $excepts = array_merge(config('admin.auth.excepts', []), [
            'admin/user/error',
            'admin/search'
        ]);
        if ($user::isValidaUrl($excepts, $path)) {
            return true;
        }

        // 校验路由权限
        $permissions = $user->allPermissions()->toArray();
        foreach ($permissions as $permission) {
            // 校验路由
            $httpPaths = explode("\n", str_replace(["\r\n", " ", ","], "\n", $permission['http_path']));
            if (!$user::isValidaUrl($httpPaths, $path)) {
                continue;
            }

            // 校验请求方式
            $method = collect(explode(",", $permission['http_method']))->filter()->map(function ($method) {
                return strtoupper($method);
            });
            if ($method->isEmpty() || $method->contains(strtoupper($request->getMethod()))) {
                return true;
            }
        }

        return false;
    }
}
