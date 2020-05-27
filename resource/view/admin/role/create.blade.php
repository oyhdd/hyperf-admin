<?php

$title = '角色列表';
$description = 'show';
$breadcrumb[] = ['text' => $title, 'url' => '/admin/roles'];
$breadcrumb[] = ['text' => '创建'];

use Oyhdd\Admin\Model\AdminPermission;

$permissions = AdminPermission::all()->pluck('name', 'id')->toArray();
$selected = array_column($model->permissions->toArray(), 'id');
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 表单创建 -->
@include('common.edit', [
    // 'action' => "/admin/roles/create",
    // 'model' => $model,
    'attributes' => [
        $model->display('id', 'Id'),
        $model->text('name', '名称')->rules('|required|required'),
        $model->text('slug', '标识')->rules('required'),
        $model->listbox('permissions', '权限')->options($permissions, $selected),
        $model->display('create_time', '创建时间'),
        $model->display('update_time', '更新时间'),
    ]
])

