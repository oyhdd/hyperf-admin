<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Controller;

use HyperfExt\Hashing\Hash;
use Oyhdd\Admin\Model\DataProvider\ModelDataProvider;

class UserController extends AdminController
{

    /**
     * List all models.
     */
    public function index()
    {
        $params = $this->request->all();
        $model = $this->getModel();
        $dataProvider = new ModelDataProvider($model, $params);

        return $this->render('admin.user.index', [
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
            if ($params['password'] !== $params['password_confirmation']) {
                admin_toastr(trans('admin.password_confirm_failed'), 'error');

                return $this->responseJson();
            }
            $params['password'] = Hash::make($params['password']);
            if ($model->fill($params) && $model->save()) {
                $model->roles()->sync($params['roles'] ?? []);
                admin_toastr(trans('admin.create_succeeded'));

                return $this->redirect('auth/user');
            }
            admin_toastr(trans('admin.create_failed'), 'error');
        }

        return $this->render('admin.user.create', [
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
            if (!empty($params['password'])) {
                if ($params['password'] !== $params['password_confirmation']) {
                    admin_toastr(trans('admin.password_confirm_failed'), 'error');

                    return $this->responseJson();
                }
                $params['password'] = Hash::make($params['password']);
            } else {
                unset($params['password']);
            }
            if ($model->fill($params) && $model->save()) {
                $model->roles()->sync($params['roles'] ?? []);
                admin_toastr(trans('admin.update_succeeded'));

                return $this->redirect('auth/user');
            }
            admin_toastr(trans('admin.update_failed'), 'error');
        }

        return $this->render('admin.user.edit', [
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

        return $this->render('admin.user.show', [
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
     * @return \Oyhdd\Admin\Model\AdminUser
     */
    protected function getModel()
    {
        return make(config('admin.database.user_model'));
    }

}