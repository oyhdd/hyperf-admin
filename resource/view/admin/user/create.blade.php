<?php

    $title = '用户列表';
    $description = 'show';
    $breadcrumb[] = ['text' => $title, 'url' => str_replace("/create", '', $_path)];
    $breadcrumb[] = ['text' => '创建'];

    $form = new \Oyhdd\Admin\Model\Widget\Form($model);
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 表单创建 -->
@include('common.form', [
    // 'form' => $form,
    // 'action' => $_path,
    'attributes' => [
        $form->text('username', '用户名')->rules('required'),
        $form->text('name', '名称')->rules('required'),
        $form->text('avatar', '头像'),
        $form->password('password', '密码')->rules('required'),
        $form->password('password_confirmation', '确认密码')->rules('required'),
        $form->multipleSelect('roles', '角色')->options(\Oyhdd\Admin\Model\AdminRole::all()->pluck('name', 'id')->toArray()),
        $form->multipleSelect('permissions', '权限')->options(\Oyhdd\Admin\Model\AdminPermission::all()->pluck('name', 'id')->toArray()),
    ]
])

