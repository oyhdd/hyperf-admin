<?php declare(strict_types=1);

namespace Oyhdd\Admin\Controller;

use Hyperf\HttpServer\Annotation\{Controller, RequestMapping, Middleware};
use Oyhdd\Admin\Middleware\AuthMiddleware;
use Oyhdd\Admin\Model\{AdminMenu, AdminRoleMenu};

/**
 * @Controller(prefix="admin/menu")
 * @Middleware(AuthMiddleware::class)
 */
class MenuController extends AdminController
{

    /**
     * @RequestMapping(path="", methods="get")
     */
    public function index()
    {
        return $this->render('admin.menu.index');
    }

    /**
     * @RequestMapping(path="create", methods="post")
     */
    public function create()
    {
        $params = $this->request->all();
        if (!empty($params['id'])) {
            $menu = AdminMenu::findOrFail($params['id']);
        } else {
            $menu = new AdminMenu();
        }
        if ($menu->fill($params)->save() && !empty($params['roles'])) {
            AdminRoleMenu::batchInsert($menu->id, $params['roles']);
        }

        return $this->redirect("/admin/menu");
    }

    /**
     * @RequestMapping(path="delete", methods="post")
     */
    public function delete()
    {
        $id = intval($this->request->input('id'));
        if (empty($id)) {
            $this->admin_toastr("Params Error", 'error', 5);
            return $this->response();
        }
        $menuIds = AdminMenu::getAll(['id'], ['parent_id' => $id]);
        $menuIds = array_column($menuIds, 'id');
        $menuIds[] = $id;
        $ret = AdminMenu::where(['id' => $id])->orWhere(['parent_id' => $id])->delete();
        if (!empty($ret)) {
            AdminRoleMenu::whereIn('menu_id', $menuIds)->delete();
            $this->admin_toastr("Delete Menu Success", 'success', 2);
        } else {
            $this->admin_toastr("Delete Menu Fail", 'error', 5);
        }
        return $this->response();
    }

    /**
     * @RequestMapping(path="{id}/edit", methods="get")
     */
    public function edit(int $id)
    {
        $model = AdminMenu::with('roles')->findOrFail($id);
        return $this->render('admin.menu.create', [
            'model' => $model->toArray()
        ]);
    }
}