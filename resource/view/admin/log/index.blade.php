<?php

    $title = '操作日志';
    $description = 'index';
    $breadcrumb[] = ['text' => $title];
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 工具框 -->
@section('card-tools')
    @include('common.tool.delete-all')
@endsection

<!-- 列表 -->
@include('common.table', [
    // 'dataProvider' => $dataProvider,
    // 'searchModel'  => $searchModel,
    'action' => [
        'delete'
    ],
    'columns' => [
        [
            'attribute' => 'id',
            'sort' => 'id',
        ],
        [
            'label' => '操作人',
            'value' => function ($model) {
                if (empty($model->user)) {
                    return '';
                }
                return $model->user->username;
            }
        ],
        [
            'label' => '请求方法',
            'value' => function ($model) {
                $color = $model::$methodColors[$model->method] ?? 'gray';
                return "<span class='badge bg-{$color}'>{$model->method}</span>";
            }
        ],
        [
            'label' => '路径',
            'value' => function ($model) {
                return "<span class='badge bg-info'>{$model->path}</span>";
            }
        ],
        [
            'label' => 'Ip',
            'value' => function ($model) {
                return "<span class='badge bg-primary'>{$model->ip}</span>";
            }
        ],
        [
            'attribute' => 'input',
            'style' => 'word-wrap:break-word;word-break:break-all;',
            'width' => 600,
        ],
        [
            'label' => '创建时间',
            'attribute' => 'create_time',
            'sort' => 'create_time',
        ]
    ]
])