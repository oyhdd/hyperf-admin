<?php declare(strict_types=1);

namespace Oyhdd\Admin\Controller;

use Psr\Container\ContainerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Contract\SessionInterface;
use Hyperf\View\RenderInterface;
use Hyperf\Utils\Context;
use Illuminate\Support\MessageBag;
use Lcobucci\JWT\Token;
use Phper666\JwtAuth\Exception\TokenValidException;
use Phper666\JwtAuth\Jwt;
use Oyhdd\Admin\Model\{AdminMenu, AdminUser};

class AdminController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @Inject()
     * @var SessionInterface
     */
    protected $session;

    /**
     * @Inject()
     * @var RenderInterface
     */
    protected $render;

    /**
     * @Inject
     * @var Jwt
     */
    protected $jwt;

    /**
     * Render view contents for the given view.
     *
     * @param  string   $view
     * @param  array    $data
     * @param  bool     $direct_render
     * @return view
     */
    protected function render(string $view, array $data = [], bool $direct_render = false)
    {
        if (!$direct_render) {
            $data['_view'] = $view;
            $view = 'layout.content';
        }

        $data['_csrf_token'] = $this->session->regenerateToken();

        $data['_toastr'] = [];
        if ($this->session->has('toastr')) {
            $data['_toastr'] = $this->session->remove('toastr');
        }
        if (!isset($data['_user'])) {
            $data['_user'] = [];
            if ($user = $this->getUser()) {
                $data['_user'] = $user->toArray();
            }
        }

        if (!empty($user)) {
            $uri = $this->request->getPathInfo();
            $data['_menu'] = AdminMenu::getMenuTree($uri, $user);
            $data['_path'] = $this->request->getPathInfo();
            $data['_full_path'] = $this->request->fullUrl();
        }

        return $this->render->render($view, compact('data'));
    }

    /**
     * Flash a toastr message bag to session.
     *
     * @param string    $message   ????????????
     * @param string    $type      ????????????: success,info,warning,danger,maroon
     * @param int       $timeout   ??????????????????
     */
    protected function admin_toastr(string $message = '', string $type = 'success', int $timeout = 2): void
    {
        $toastr = new MessageBag(get_defined_vars());
        $this->session->flash('toastr', $toastr);
    }

    /**
     * get Token Object from cookie
     *
     * @return \Lcobucci\JWT\Token
     */
    protected function getTokenObj(): ?Token
    {
        try {
            $token = $this->request->cookie('Authorization', '');
            $token = ucfirst($token);
            $arr = explode('Bearer ', ucfirst($token));
            $token = $arr[1] ?? '';
            if (!empty($token) && $this->jwt->checkToken($token)) {
                return $this->jwt->getTokenObj($token);
            }
        } catch (TokenValidException $t) {
        }

        return null;
    }

    /**
     * ????????????
     * @param  array       $data
     * @param  int         $code
     * @param  string      $msg
     * @return array
     */
    public function response($data = [], int $code = 0, string $msg = 'success'): array
    {
        return compact('code', 'msg', 'data');
    }

    /**
     * Redirect to a url with a status.
     */
    public function redirect(string $toUrl, int $status = 302, string $schema = 'http')
    {
        return $this->response->redirect($toUrl, $status, $schema);
    }

    /**
     * get User Info
     * @return AdminUser
     */
    public function getUser(): ?AdminUser
    {
        return $this->request->getAttribute('user', null);
    }
}