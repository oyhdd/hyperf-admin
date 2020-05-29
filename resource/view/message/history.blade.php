<?php

$title = '消息操作记录';
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
    'dataProvider' => $dataProvider,
    'action' => [
        'delete'
    ],
    'columns' => [
        'id',
        [
            'label' => '消息类型',
            'value' => function ($model) {
                return $model->messageType->topic ?? '';
            }
        ],
        [
            'attribute' => 'remark',
            'label' => '备注',
            'width' => 100,
        ],
        'ip',
        [
            'attribute' => 'content',
            'label' => '内容',
            'width' => 150,
        ],
        [
            'label' => '操作人',
            'width' => 80,
            'value' => function ($model) {
                if (empty($model->user)) {
                    return '';
                }
                return $model->user->username;
            }
        ],
        [
            'attribute' => 'create_time',
            'label' => '创建时间',
        ]
    ]
])

@include('common.toastr')
<script type="text/javascript">
    $(function () {
        $.dataTablesSettings.searching = true;

        $(".admin-table").DataTable($.dataTablesSettings);

    })
</script>
