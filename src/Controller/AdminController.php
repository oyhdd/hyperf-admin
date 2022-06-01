<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Contract\SessionInterface;

use function Hyperf\ViewEngine\view;

class AdminController
{

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


    public function index()
    {
        return $this->render('index');
    }

    public function error()
    {
        return $this->render('common.error');
    }

    /**
     * Render view contents for the given view.
     *
     * @param string    $view
     * @param array     $data
     */
    protected function render(string $view, array $data = [])
    {
        $path = $this->request->getPathInfo();
        $_data = [
            'view' => $view,
            'user' => admin_user(),
            'menu' => make(config('admin.database.menu_model'))->getMenuTree(admin_url_without_prefix($path)),
            'path' => $path,
            'site' => make(config('admin.database.site_model'))->getAll(),
            'is_ajax' => $this->request->header('X-PJAX') === 'true',
            'ha_no_animation' => $this->request->input('_ha_no_animation', 0),
            'query' => $this->request->query(),
        ];

        if ($_data['is_ajax']) {
            return view('layout.content', compact('data', '_data'));
        }

        return view('layout.main', compact('data', '_data'));
    }

    /**
     * Render full view contents for the given view.
     *
     * @param string    $view
     * @param array     $data
     */
    protected function renderFull(string $view, array $data = [])
    {
        return view($view, compact('data'));
    }

    /**
     * 接口返回
     *
     * @param  array       $data
     * @param  int         $code
     * @param  string      $msg
     * @return array
     */
    protected function responseJson(array $data = [], int $code = 0, string $msg = 'success'): array
    {
        return compact('code', 'msg', 'data');
    }
}
