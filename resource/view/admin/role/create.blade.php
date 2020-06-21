<?php

    $title = '角色列表';
    $description = 'show';
    $breadcrumb[] = ['text' => $title, 'url' => str_replace("/create", '', $_path)];
    $breadcrumb[] = ['text' => '创建'];

    $permissions = \Oyhdd\Admin\Model\AdminPermission::all()->pluck('name', 'id')->toArray();
    $selected = array_column($model->permissions->toArray(), 'id');

    $form = new \Oyhdd\Admin\Model\Widget\Form($model);
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 表单创建 -->
@include('common.form', [
    // 'form' => $form,
    // 'action' => $_path,
    'attributes' => [
        $form->text('name', '名称')->rules('required'),
        $form->text('slug', '标识')->rules('required'),
        $form->listbox('permissions', '权限')->options($permissions, $selected),
    ]
])

