<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Controller;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Contract\SessionInterface;
use Hyperf\Utils\Arr;

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

    /**
     * Export data to excel from model.
     *
     * @param $is_all
     * @param $is_page
     * @param $id
     * @param $_perPage
     * @param $_page
     */
    public function export()
    {
        $params = $this->request->all();

        return $this->getModel()->export($params);
    }

    /**
     * @return \Oyhdd\Admin\Model\BaseModel
     */
    protected function getModel()
    {
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
            'is_pjax' => $this->request->header('X-PJAX') === 'true',
            'ha_no_animation' => $this->request->input('_ha_no_animation', 0),
            'query' => $this->request->query(),
        ];
        if ($_data['is_pjax']) {
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
        if ($messageBag = get_toastr()) {
            $site = make(config('admin.database.site_model'))->getAll();
            $type    = Arr::get($messageBag->get('type'), 0, 'success');
            $message = Arr::get($messageBag->get('message'), 0, '');
            $timeout = (intval($site['toastr_timeout'] ?? 4)) * 1000;
            $data['_toastr'] = compact('type', 'message', 'timeout');
        }

        return compact('code', 'msg', 'data');
    }

    protected function redirect(string $url): array
    {
        $_redirect = admin_url($url);

        return $this->responseJson(compact('_redirect'));
    }
}
