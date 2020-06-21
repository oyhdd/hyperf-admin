<?php

    use Oyhdd\Admin\Model\{AdminMenu, AdminRole, AdminPermission};

    $title = '菜单';
    $description = '编辑';
    $breadcrumb[] = ['text' => $title, 'url' => '/admin/menu'];
    $breadcrumb[] = ['text' => '编辑'];

    $menus = AdminMenu::buildSelectOptions($data['_menu']);

    $roles = AdminRole::getAll(['id', 'name']);
    $roles = array_column($roles, 'name', 'id');


    $is_new = false;
    if (isset($data['model'])) {
        $model = $data['model'];
    } else {
        $model = new AdminMenu();
        $is_new = true;
    }

    $permissions = AdminPermission::getAll(['slug', 'name']);
    $permissions = array_column($permissions, 'name', 'slug');

    $menuRole = [];
    if (!$is_new && !empty($model->roles)) {
        $menuRole = $model->roles->pluck('id')->toArray();
    }

    $form = new \Oyhdd\Admin\Model\Widget\Form($model);
?>

@if(!$is_new)
    @include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))
@endif

<!-- 表单创建 -->
@include('common.form', [
    // 'form' => $form,
    'action' => "/admin/menu/create",
    'attributes' => [
        $form->display('id', 'Id')->delete($is_new),
        $form->select('parent_id', '父级菜单')->options([0 => "ROOT"] + array_column($menus, 'title', 'id'), [$model->parent_id])->rules('required'),
        $form->text('title', '标题')->rules('required'),
        $form->text('icon', '图标', 'fa-bars')->default('fa-bars')->rules('required')->help('For more icons please see <a href="https://fontawesome.com/" target="_blank">https://fontawesome.com/</a>'),
        $form->text('order', '排序')->help('数值越小越靠前, 最小为0'),
        $form->text('uri', '路径'),
        $form->multipleSelect('roles', '角色')->options($roles, $menuRole),
        $form->select('permission', '权限')->options($permissions, [$model->permission]),
    ]
])
