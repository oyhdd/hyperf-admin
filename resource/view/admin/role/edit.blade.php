<?php

$title = '角色列表';
$description = 'show';
$breadcrumb[] = ['text' => $title, 'url' => '/admin/roles'];
$breadcrumb[] = ['text' => '编辑'];

use Oyhdd\Admin\Model\AdminPermission;

$permissions = AdminPermission::all()->pluck('name', 'id')->toArray();
$selected = array_column($model->permissions->toArray(), 'id');
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 工具框 -->
@section('card-tools')
    <a class='btn btn-primary ml-2' href="{{ str_replace('/edit', '', $data['_path']) }}"><i class="fa fa-eye"></i> {{ trans('admin.show') }}</a>
    <a href="javascript:void(0);" class="btn btn-danger ml-2 model-delete"><i class="fas fa-trash"></i> {{ trans('admin.delete') }}</a>
@endsection

<!-- 表单编辑 -->
@include('common.edit', [
    // 'action' => "/admin/roles/{$model->id}/edit",
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

