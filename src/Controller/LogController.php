<?php declare(strict_types=1);

namespace Oyhdd\Admin\Controller;

use Hyperf\HttpServer\Annotation\{Controller, RequestMapping, Middleware};
use Oyhdd\Admin\Middleware\AuthMiddleware;
use Oyhdd\Admin\Search\AdminOperationLogSearch;
use Hyperf\Di\Annotation\Inject;

/**
 * @Controller(prefix="admin/logs")
 * @Middleware(AuthMiddleware::class)
 */
class LogController extends AdminController
{
    /**
     * @Inject
     * @var AdminOperationLogSearch
     */
    protected $opLogSearch;

    /**
     * @RequestMapping(path="", methods="get")
     */
    public function index()
    {
        $params = $this->request->all();
        $dataProvider = $this->opLogSearch->search($params);

        return $this->render('admin.log.index', [
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }

    /**
     * @RequestMapping(path="delete-all", methods="post")
     */
    public function deleteAll()
    {
        $ret = AdminOperationLogSearch::truncate();
        if (!empty($ret)) {
            $this->admin_toastr("Delete All Success", 'success', 2);
        } else {
            $this->admin_toastr("Delete All Fail", 'error', 5);
        }
        return $this->response();
    }

    /**
     * @RequestMapping(path="{id}/delete", methods="post")
     */
    public function delete($id)
    {
        if (AdminOperationLogSearch::where('id', $id)->delete()) {
            $this->admin_toastr("Delete Success", 'success', 2);
        } else {
            $this->admin_toastr("Delete Fail", 'error', 5);
        }

        return $this->response();
    }
}