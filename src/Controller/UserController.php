<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Controller;

use Illuminate\Hashing\BcryptHasher;
use Hyperf\Di\Annotation\Inject;
use Oyhdd\Admin\Model\DataProvider\ModelDataProvider;

class UserController extends AdminController
{
    /**
     * @Inject
     * @var BcryptHasher
     */
    protected $hash;

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
            } else {
                $params['password'] = $this->hash->make($params['password']);
                if ($model->fill($params) && $model->save()) {
                    $model->roles()->sync($params['roles'] ?? []);
                    admin_toastr(trans('admin.create_succeeded'));
                    return $this->response->redirect(admin_url('auth/user'));
                }
            }
        }

        return $this->render('admin.user.create', [
            'model' => $model,
        ]);
    }

    /**
    * Updates an existing model.
    * @param int $id
    */
   public function edit($id)
   {
        $model = $this->getModel()->query()->findOrFail($id);
var_dump($model->toArray());
        if ($this->request->isMethod('post')) {
            $params = $this->request->all();
            if ($params['password'] !== $params['password_confirmation']) {
                admin_toastr(trans('admin.password_confirm_failed'), 'error');
            } else {
                $params['password'] = $this->hash->make($params['password']);
                if ($model->fill($params) && $model->save()) {
                    $model->roles()->sync($params['roles'] ?? []);
                    admin_toastr(trans('admin.create_succeeded'));
                    return $this->response->redirect(admin_url('auth/user'));
                }
            }
        }

        return $this->render('admin.user.edit', [
            'model' => $model,
        ]);
   }

    /**
     * Delete an existing model.
     * 
     * @param  int $id
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
     * @param int       $is_all
     * @param int       $is_page
     * @param string    $id
     * @param int       $_perPage
     * @param int       $_page
     */
    public function export()
    {
        $params = $this->request->all();

        return $this->getModel()->export($params, ['id', 'username', 'name', 'created_at', 'updated_at']);
    }

    /**
     * @return \Oyhdd\Admin\Model\AdminUser
     */
    protected function getModel()
    {
        return make(config('admin.database.user_model'));
    }

}