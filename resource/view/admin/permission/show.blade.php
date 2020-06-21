<?php

    $title = '权限列表';
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
            'label' => '请求方法',
            'value' => function ($model) {
                $httpMethods = explode(',', trim($model->http_method, ','));
                $label = "";
                foreach ($httpMethods as $http_method) {
                    if (empty($http_method)) {
                        $label = "<span class='badge bg-primary'>ANY</span>&nbsp";
                    } else {
                        $label .= "<span class='badge bg-primary'>{$http_method}</span>&nbsp";
                    }
                }
                return $label;
            }
        ],
        [
            'label' => '路由',
            'value' => function ($model) {
                $http_path = str_replace(["\r\n", "\n", "\r", ",", " "], '<br>', $model->http_path);
                return empty($http_path) ? 'admin' : $http_path;
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