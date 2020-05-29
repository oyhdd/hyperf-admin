<?php

$title = '用户列表';
$description = 'show';
$breadcrumb[] = ['text' => $title, 'url' => str_replace("/{$model->id}/edit", '', $_path)];
$breadcrumb[] = ['text' => '编辑'];

$permissions = \Oyhdd\Admin\Model\AdminPermission::all()->pluck('name', 'id')->toArray();
$selected = array_column($model->permissions->toArray(), 'id');

$form = new \Oyhdd\Admin\Model\Widget\Form($model);
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 工具框 -->
@section('card-tools')
    <a class='btn btn-primary ml-2' href="{{ str_replace('/edit', '', $data['_path']) }}"><i class="fa fa-eye"></i> {{ trans('admin.show') }}</a>
    @if($model->id != 1)
    <a href="javascript:void(0);" class="btn btn-danger ml-2 model-delete"><i class="fas fa-trash"></i> {{ trans('admin.delete') }}</a>
    @endif
@endsection

<!-- 表单编辑 -->
@include('common.edit', [
    // 'action' => $_path,
    // 'form' => $form,
    'attributes' => [
        $form->display('id', 'Id'),
        $form->text('username', '用户名')->rules('required'),
        $form->text('name', '名称')->rules('required'),
        $form->text('avatar', '头像'),
        $form->password('password', '密码')->rules('required'),
        $form->password('password_confirmation', '确认密码')->rules('required'),
        $form->multipleSelect('roles', '角色')->options(
            \Oyhdd\Admin\Model\AdminRole::all()->pluck('name', 'id')->toArray(),
            array_column($model->roles->toArray(), 'id'),
        ),
        $form->multipleSelect('permissions', '权限')->options(
            \Oyhdd\Admin\Model\AdminPermission::all()->pluck('name', 'id')->toArray(),
            array_column($model->permissions->toArray(), 'id'),
        ),
        $form->display('create_time', '创建时间'),
        $form->display('update_time', '更新时间'),
    ]
])

