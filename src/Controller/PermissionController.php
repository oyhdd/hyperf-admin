<?php declare(strict_types=1);

namespace Oyhdd\Admin\Controller;

use Hyperf\HttpServer\Annotation\{Controller, RequestMapping, Middleware};
use Hyperf\Di\Annotation\Inject;
use Oyhdd\Admin\Middleware\AuthMiddleware;
use Oyhdd\Admin\Search\AdminPermissionSearch;

/**
 * @Controller(prefix="admin/permission")
 * @Middleware(AuthMiddleware::class)
 */
class PermissionController extends AdminController
{
    /**
     * @Inject
     * @var AdminPermissionSearch
     */
    protected $adminPermissionSearch;

    /**
     * @RequestMapping(path="", methods="get")
     * 
     * Lists all models.
     * @return mixed
     */
    public function index()
    {
        $params = $this->request->all();
        $dataProvider = $this->adminPermissionSearch->search($params);

        return $this->render('admin.permission.index', [
            'dataProvider' => $dataProvider,
            'params'       => $params,
        ]);
    }

    /**
     * @RequestMapping(path="create")
     * 
     * Creates a new model.
     * @return mixed
     */
    public function create()
    {
        $model = new AdminPermissionSearch();

        if ($model->fill($this->request->all()) && $model->save()) {
            $this->admin_toastr("Create Success", 'success', 2);
            return $this->redirect("admin/permission");
        }

        return $this->render('admin.permission.create', [
            'model' => $model,
        ]);
    }

    /**
     * @RequestMapping(path="{id}/edit")
     * 
     * Updates an existing model.
     * @param  int $id
     * @return mixed
     */
    public function edit($id)
    {
        $model = AdminPermissionSearch::findOrFail($id);

        if ($model->fill($this->request->all()) && $model->save()) {
            $this->admin_toastr("Edit Success", 'success', 2);
            return $this->redirect("admin/permission/{$id}");
        }

        return $this->render('admin.permission.edit', [
            'model' => $model,
        ]);
    }

    /**
     * @RequestMapping(path="{id}", methods="get")
     * 
     * Displays a single model.
     * @author Eric
     * @param  int $id
     * @return mixed
     */
    public function show(int $id)
    {
        $model = AdminPermissionSearch::findOrFail($id);

        return $this->render('admin.permission.show', [
            'model' => $model,
        ]);
    }

    /**
     * @RequestMapping(path="{id}/delete", methods="post")
     * 
     * Deletes an existing model.
     * @param  int $id
     * @return mixed
     */
    public function delete($id)
    {
        if (AdminPermissionSearch::where('id', $id)->delete()) {
            $this->admin_toastr("Delete Success", 'success', 2);
        } else {
            $this->admin_toastr("Delete Fail", 'error', 5);
        }

        return $this->response();
    }

}