<?php

    $title = '用户列表';
    $description = 'index';
    $breadcrumb[] = ['text' => $title];
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 工具框 -->
@section('card-tools')
    <a class='btn btn-success ml-2' href="{{ $_path }}/create"><i class="fa fa-plus"></i> {{ trans('admin.create') }}</a>
@endsection

<!-- 列表 -->
@include('common.table', [
    // 'dataProvider' => $dataProvider,
    // 'searchModel'  => $searchModel,
    'action' => [
        'view',
        'edit',
        function ($model) {
            if ( $model->id != 1) {
                return "<a href='javascript:void(0);' data-id='{{ $model->id }}' class='text-danger grid-row-delete' title='删除'><i class='fas fa-trash'></i></a>";
            }
            return '';
        }
    ],
    'columns' => [
        [
            'attribute' => 'id',
            'sort' => 'id',
        ],
        [
            'label' => '用户名',
            'attribute' => 'username',
            'sort' => 'username',
        ],
        [
            'label' => '名称',
            'attribute' => 'name',
            'sort' => 'name',
        ],
        [
            'label' => '角色',
            'value' => function ($model) {
                $html = "";
                foreach ($model->roles as $role) {
                    $html .= "<span class='badge bg-success'>{$role->name}</span>&nbsp;";
                }
                return $html;
            }
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
            'sort' => 'create_time',
        ],
        [
            'label' => '更新时间',
            'attribute' => 'update_time',
            'sort' => 'update_time',
        ]
    ]
])