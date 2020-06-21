<?php

    $title = '角色列表';
    $description = 'show';
    $breadcrumb[] = ['text' => $title, 'url' => str_replace("/{$model->id}", '', $_path)];
    $breadcrumb[] = ['text' => '详情'];
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb')

<!-- 工具框 -->
@section('card-tools')
    <a class='btn btn-primary ml-2' href="{{ $data['_path'] }}/edit"><i class="fa fa-edit"></i> {{ trans('admin.edit') }}</a>
    <a href="javascript:void(0);" class="btn btn-danger ml-2 model-delete"><i class="fas fa-trash"></i> {{ trans('admin.delete') }}</a>
@endsection

<!-- 表单显示 -->
@include('common.show', [
    // 'model' => $model,
    'attributes' => [
        'id',
        [
            'label' => '名称',
            'attribute' => 'name',
        ],
        [
            'label' => '标识',
            'attribute' => 'slug',
        ],
        [
            'label' => '权限',
            'value' => function ($model) {
                $html = "";
                foreach ($model->permissions as $permission) {
                    $html .= "<span class='badge bg-success'>{$permission->name}</span>&nbsp;";
                }
                return $html;
            }
        ],
        [
            'label' => '创建时间',
            'attribute' => 'create_time',
        ],
        [
            'label' => '更新时间',
            'attribute' => 'update_time',
        ]
    ]
])