<?php

$title = '操作日志';
$description = 'index';
$breadcrumb[] = ['text' => $title];

?>
<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 工具框 -->
@section('card-tools')
    @include('widget.tool.delete-all')
@endsection

<!-- 列表 -->
@include('widget.table', [
    // 'dataProvider' => $dataProvider,
    // '_path' => $_path,
    'action' => [
        'delete'
    ],
    'columns' => [
        'id',
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
            'width' => 200,
        ],
        [
            'attribute' => 'create_time',
            'label' => '创建时间',
        ]
    ]
])

@include('widget.toastr')
<script type="text/javascript">
    $(function () {
        $.dataTablesSettings.searching = true;
        $.dataTablesSettings.columnDefs = [{
            'targets' : [5],
            'orderable' : false
        }];

        $(".admin-table").DataTable($.dataTablesSettings);
    })
</script>