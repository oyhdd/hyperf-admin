<?php

$title = '角色';
$description = 'index';
$breadcrumb[] = ['text' => $title];

?>
<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 工具框 -->
@section('card-tools')
<a class='btn btn-success float-right' href="{{ $_path }}/create"><i class="fa fa-plus"></i> {{ trans('admin.create') }}</a>
@endsection

<!-- 列表 -->
@include('common.table', [
    // 'dataProvider' => $dataProvider,
    // '_path' => $_path,
    'action' => [
        'view',
        'edit',
        'delete',
    ],
    'columns' => [
        'id',
        [
            'label' => '名称',
            'attribute' => 'name'
        ],
        [
            'label' => '标识',
            'attribute' => 'slug'
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
            'attribute' => 'create_time',
            'label' => '创建时间',
        ],
        [
            'attribute' => 'update_time',
            'label' => '更新时间',
        ]
    ]
])

<script type="text/javascript">
    $(function () {
        // $.dataTablesSettings.searching = true;
        $.dataTablesSettings.columnDefs = [{
            'targets' : [5],
            'orderable' : false
        }];

        $(".admin-table").DataTable($.dataTablesSettings);
    })
</script>