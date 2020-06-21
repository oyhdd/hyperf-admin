<?php

    $title = '权限';
    $description = 'index';
    $breadcrumb[] = ['text' => $title];
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 工具框 -->
@section('card-tools')
    <a class='btn btn-success' href="{{ $_path }}/create"><i class="fa fa-plus"></i> {{ trans('admin.create') }}</a>
@endsection

<!-- 列表 -->
@include('common.table', [
    // 'dataProvider' => $dataProvider,
    // 'searchModel'  => $searchModel,
    'action' => [
        'view',
        'edit',
        'delete',
    ],
    'columns' => [
        [
            'attribute' => 'id',
            'sort' => 'id',
        ],
        [
            'label' => '名称',
            'attribute' => 'name',
            'sort' => 'id',
        ],
        [
            'label' => '标识',
            'attribute' => 'slug',
            'sort' => 'id',
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
            'sort' => 'id',
        ],
        [
            'label' => '更新时间',
            'attribute' => 'update_time',
            'sort' => 'id',
        ]
    ]
])