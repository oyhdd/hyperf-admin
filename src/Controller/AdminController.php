<?php declare(strict_types=1);

namespace Oyhdd\Admin\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Hyperf\Contract\SessionInterface;
use Hyperf\View\RenderInterface;
use Phper666\JwtAuth\Jwt;
use Lcobucci\JWT\Token;
use Illuminate\Support\MessageBag;
use Oyhdd\Admin\Model\AdminMenu;
use Phper666\JwtAuth\Exception\TokenValidException;

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
            if ($user = $this->request->getAttribute('user')) {
                $data['_user'] = $user->toArray();
            }
        }

        if (!empty($user)) {
            $uri = $this->request->getPathInfo();
            $data['_menu'] = AdminMenu::getMenuTree($uri, $user);
            $data['_path'] = '/'.$this->request->path();
        }

        return $this->render->render($view, compact('data'));
    }

    /**
     * Flash a toastr message bag to session.
     *
     * @param string    $message   提示消息
     * @param string    $type      消息类型: success,info,warning,danger,maroon
     * @param int       $timeout   超时自动隐藏
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
            // var_dump("AdminController: ".$t->getMessage());
        }

        return null;
    }

    /**
     * 接口返回
     * @author Eric
     * @param  array       $data
     * @param  int         $code
     * @param  string      $msg
     * @return array
     */
    public function response(array $data = [], int $code = 0, string $msg = 'success'): array
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
}