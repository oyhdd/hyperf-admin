<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Controller;

use Oyhdd\Admin\Model\DataProvider\ModelDataProvider;

class RoleController extends AdminController
{
    /**
     * List all models.
     */
    public function index()
    {
        $params = $this->request->all();
        $model = $this->getModel();
        $dataProvider = new ModelDataProvider($model, $params);

        return $this->render('admin.role.index', [
            'model'        => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Create a new model.
     */
    public function create()
    {
        $model = $this->getModel();

        if ($this->request->isMethod('post')) {
            $params = $this->request->all();
            if ($model->fill($params) && $model->save()) {
                $model->permissions()->sync($params['permissions'] ?? []);
                admin_toastr(trans('admin.create_succeeded'));

                return $this->redirect('auth/role');
            }
            admin_toastr(trans('admin.create_failed'), 'error');
        }

        return $this->render('admin.role.create', [
            'model' => $model,
        ]);
    }

    /**
    * Update an existing model.
    *
    * @param $id
    */
   public function edit($id)
   {
        $model = $this->getModel()->query()->findOrFail($id);

        if ($this->request->isMethod('post')) {
            $params = $this->request->all();
            if ($model->fill($params) && $model->save()) {
                $model->permissions()->sync($params['permissions'] ?? []);
                admin_toastr(trans('admin.update_succeeded'));

                return $this->redirect('auth/role');
            }
            admin_toastr(trans('admin.update_failed'), 'error');
        }

        return $this->render('admin.role.edit', [
            'model' => $model,
        ]);
   }

    /**
     * Display a single model.
     *
     * @param $id
     */
    public function show($id)
    {
        $model = $this->getModel()->query()->findOrFail($id);

        return $this->render('admin.role.show', [
            'model' => $model
        ]);
    }

    /**
     * Delete an existing model.
     *
     * @param $id
     */
    public function delete()
    {
        $ids = explode(',', $this->request->input('id', ''));
        if (!empty($ids) && $this->getModel()->destroy($ids)) {
            admin_toastr(trans("admin.delete_succeeded"));
        } else {
            admin_toastr(trans("admin.delete_failed"));
        }

        return $this->responseJson();
    }

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
     * @return \Oyhdd\Admin\Model\AdminRole
     */
    protected function getModel()
    {
        return make(config('admin.database.role_model'));
    }

}