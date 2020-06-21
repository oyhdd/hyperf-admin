<?php declare(strict_types=1);

namespace Oyhdd\Admin\Controller;

use Hyperf\HttpServer\Annotation\{Controller, RequestMapping, Middleware};
use Hyperf\Di\Annotation\Inject;
use Oyhdd\Admin\Middleware\AuthMiddleware;
use Oyhdd\Admin\Search\AdminRoleSearch;

/**
 * @Controller(prefix="admin/role")
 * @Middleware(AuthMiddleware::class)
 */
class RoleController extends AdminController
{
    /**
     * @Inject
     * @var AdminRoleSearch
     */
    protected $adminRoleSearch;

    /**
     * @RequestMapping(path="", methods="get")
     *
     * Lists all models.
     * @return mixed
     */
    public function index()
    {
        $params = $this->request->all();

        $dataProvider = $this->adminRoleSearch->search($params);
        $searchModel = $this->adminRoleSearch->searchForm($params);

        return $this->render('admin.role.index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
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
        $model = new AdminRoleSearch();

        if ($model->fill($this->request->all()) && $model->save()) {
            $model->permissions()->sync($this->request->input('permissions'));
            $this->admin_toastr("Create Success", 'success', 2);
            return $this->redirect("admin/role");
        }

        return $this->render('admin.role.create', [
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
        $model = AdminRoleSearch::findOrFail($id);

        if ($model->fill($this->request->all()) && $model->save()) {
            $model->permissions()->sync($this->request->input('permissions'));
            $this->admin_toastr("Edit Success", 'success', 2);
            return $this->redirect("admin/role/{$id}");
        }

        return $this->render('admin.role.edit', [
            'model' => $model,
        ]);
    }

    /**
     * @RequestMapping(path="{id}", methods="get")
     *
     * Displays a single model.
     * @param  int $id
     * @return mixed
     */
    public function show(int $id)
    {
        $model = AdminRoleSearch::findOrFail($id);

        return $this->render('admin.role.show', [
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
        if (AdminRoleSearch::where('id', $id)->delete()) {
            $this->admin_toastr("Delete Success", 'success', 2);
        } else {
            $this->admin_toastr("Delete Fail", 'error', 5);
        }

        return $this->response();
    }

}