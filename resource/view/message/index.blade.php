<?php

$title = '消息列表';
$description = 'index';
$breadcrumb[] = ['text' => $title];

use App\Model\MessageType;
?>
<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 工具框 -->
@section('card-tools')
<form method="get">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">消息类型</span>
        </div>
        <select class="form-control select2 col-sm-6" name="push_type">
            <option value ="0">全部</option>
            @foreach(MessageType::$push_type_label as $push_type => $push_type_label)
            <option value ="{{ $push_type }}" {{ (isset($params['push_type']) && $push_type == $params['push_type']) ? "selected" : "" }}>{{ $push_type_label }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary ml-2">搜索</button>
        <a class='btn btn-success ml-2' href="/admin/message/create"><i class="fa fa-plus"></i> 创建</a>
    </div>
</form>
@endsection

<!-- 列表 -->
@include('common.table', [
    // 'dataProvider' => $dataProvider,
    // '_path' => $_path,
    'action' => [
        'view',
        function ($model) use($_path) {
            // if ($model->push_type == $model::PUSH_TYPE_ACTIVITY && $model->state == $model::STATE_WAIT) {
            if ($model->state == $model::STATE_WAIT) {
                return "<a href='{$_path}/{$model->id}/edit' class='text-primary' title='编辑'><i class='fas fa-edit'></i></a>";
            }
            return '';
        },
        function ($model) {
            if ($model->push_type == $model::PUSH_TYPE_ACTIVITY && $model->state < $model::STATE_SEND_SUCCESS) {
                return "<a href='javascript:void(0);' data-id='{$model->id}' class='text-danger grid-row-delete' title='取消推送'><i class='fas fa-trash'></i></a>";
            }
            return '';
        }
    ],
    'columns' => [
        'id',
        [
            'attribute' => 'topic',
            'label' => '消息主题',
        ],
        [
            'label' => '消息类型',
            'value' => function ($model) {
                return $model::$push_type_label[$model->push_type] ?? '';
            }
        ],
        [
            'attribute' => 'remark',
            'label' => '备注',
            'width' => 200,
        ],
        [
            'attribute' => 'push_time',
            'label' => '推送时间',
            'value' => function ($model) {
                if ($model->push_type == $model::PUSH_TYPE_BUSINESS) {
                    return '触发时推送';
                }
                return $model->push_time;
            }
        ],
        [
            'label' => '推送状态',
            'value' => function ($model) {
                $label = $model::$state_label[$model->state] ?? '';
                if ($model->state == $model::STATE_WAIT) {
                    return "<span class='badge bg-primary'>{$label}</span>";
                } elseif ($model->state == $model::STATE_SENDING) {
                    return "<span class='badge bg-warning'>{$label}</span>";
                } elseif ($model->state == $model::STATE_SEND_CANCEL) {
                    return "<span class='badge bg-gray'>{$label}</span>";
                } else {
                    return "<span class='badge bg-success'>{$label}</span>";
                }
            }
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
            'attribute' => 'create_time',
            'label' => '创建时间',
        ],
        [
            'label' => '推送测试',
            'value' => function ($model) {
                if ($model->state != $model::STATE_SEND_CANCEL) {
                    return "<a href='javascript:void(0);' data-id='{$model->id}' class='text-success push_message' title='推送消息'><i class='fas fa-paper-plane'></i></a>";
                }
                return '';
            }
        ],
    ]
])

@include('common.toastr')
<script type="text/javascript">
    $(function () {
        $.dataTablesSettings.searching = false;
        $.dataTablesSettings.columnDefs = [{
            'targets' : [3,6,7],
            'orderable' : false
        }];

        $(".admin-table").DataTable($.dataTablesSettings);

        $('.grid-row-delete').unbind('click').click(function() {
            var id = $(this).data('id');

            Swal.fire({
                type: 'warning', // 弹框类型
                title: '确认取消推送', // 标题
                text: "取消推送后将无法恢复，请确认！", //显示内容

                confirmButtonColor: '#DD6B55',// 确定按钮的 颜色
                confirmButtonText: '确认',// 确定按钮的 文字
                showCancelButton: true, // 是否显示取消按钮
                cancelButtonText: "取消", // 取消按钮的 文字
            }).then((isConfirm) => {
                try {
                    if (isConfirm.value) {
                        $.ajax({
                            method: 'post',
                            url: '/admin/message/cancel',
                            data: {
                                "id": id
                            },
                            success: function (data) {
                                window.location.reload();
                            },
                            error: function(data) {
                            }
                        });
                    }
                } catch (e) {
                    Swal.fire("Error", "请求失败，请稍后重试！", "error");
                }
            });
        });

        $('.push_message').unbind('click').click(function() {
            var id = $(this).data('id');

            Swal.fire({
                title: '推送消息',
                html:
                    '<label>用户邮箱:</label>&nbsp;&nbsp;<input id="push_email" class="swal2-input" style="width: 60%;" required><br>' +
                    '<label>订单号:</label>&nbsp;&nbsp;<input id="push_order_sn" class="swal2-input" style="width: 60%;"><br>' +
                    '<label>商品id:</label>&nbsp;&nbsp;<input id="push_goods_id" class="swal2-input" style="width: 60%;"><br>',
                confirmButtonColor: '#DD6B55',// 确定按钮的 颜色
                confirmButtonText: '确认',// 确定按钮的 文字
                showCancelButton: true, // 是否显示取消按钮
                cancelButtonText: "取消", // 取消按钮的 文字
                preConfirm: () => {
                    $.ajax({
                        method: 'post',
                        url: '/admin/message/push',
                        data: {
                            "message_type": id,
                            "email": $('#push_email').val(),
                            "order_sn": $('#push_order_sn').val(),
                            "goods_id": $('#push_goods_id').val()
                        },
                        success: function (data) {
                            window.location.reload();
                        },
                        error: function(data) {
                        }
                    });
                }
            });
        });
    })
</script>
