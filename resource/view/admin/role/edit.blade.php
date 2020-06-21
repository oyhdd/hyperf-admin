<?php

    $title = '角色列表';
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
    <a href="javascript:void(0);" class="btn btn-danger ml-2 model-delete"><i class="fas fa-trash"></i> {{ trans('admin.delete') }}</a>
@endsection

<!-- 表单编辑 -->
@include('common.form', [
    // 'form' => $form,
    // 'action' => $_path,
    'attributes' => [
        $form->display('id', 'Id'),
        $form->text('name', '名称')->rules('required'),
        $form->text('slug', '标识')->rules('required'),
        $form->listbox('permissions', '权限')->options($permissions, $selected),
        $form->display('create_time', '创建时间'),
        $form->display('update_time', '更新时间'),
    ]
])

