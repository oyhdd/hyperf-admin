<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Controller;

class SiteController extends AdminController
{
    /**
     * Updates an existing AdminSite model.
     */
    public function edit()
    {
        $model = $this->getModel();
        if ($this->request->isMethod('POST')) {
            if ($model->saveData($this->request->all())) {
                admin_toastr(trans('admin.update_succeeded'));
            } else {
                admin_toastr(trans('admin.update_failed'), 'error');
            }

            return $this->redirect('auth/site/edit');
        }

        return $this->render('admin.auth.site', [
            'model' => $model,
        ]);
    }


    /**
     * @return \Oyhdd\Admin\Model\AdminSite
     */
    protected function getModel()
    {
        return make(config('admin.database.site_model'));
    }

}