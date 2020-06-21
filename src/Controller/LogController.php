<?php declare(strict_types=1);

namespace Oyhdd\Admin\Controller;

use Hyperf\HttpServer\Annotation\{Controller, RequestMapping, Middleware};
use Hyperf\Di\Annotation\Inject;
use Oyhdd\Admin\Middleware\AuthMiddleware;
use Oyhdd\Admin\Search\AdminOperationLogSearch;

/**
 * @Controller(prefix="admin/log")
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
        $params['page_size'] = 5;

        $dataProvider = $this->opLogSearch->search($params);
        $searchModel  = $this->opLogSearch->searchForm($params);

        return $this->render('admin.log.index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'params'       => $params,
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