<?php
    $title = '菜单';
    $description = '列表';
    $breadcrumb[] = ['text' => $title, 'url' => '/admin'];
?>
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ trans('admin.menu') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="/admin/menu" data-source-selector="#refresh_menu">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="todo-list" data-widget="todo-list">
                        @each('admin.menu.list', $data['_menu'], 'item')
                    </ul>
                </div>
            </div>
            <div class="d-none" id="refresh_menu">
                <ul class="todo-list" data-widget="todo-list">
                    @each('admin.menu.list', $data['_menu'], 'item')
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            @include('admin.menu.create')
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('.tree_branch_delete').unbind('click').click(function() {
            var id = $(this).data('id');

            Swal.fire({
                type: 'warning', // 弹框类型
                title: '确认删除该菜单及其子菜单', // 标题
                text: "删除该菜单将无法恢复，请确认！", //显示内容
                confirmButtonColor: '#DD6B55',// 确定按钮的 颜色
                confirmButtonText: '确认',// 确定按钮的 文字
                showCancelButton: true, // 是否显示取消按钮
                cancelButtonText: "取消", // 取消按钮的 文字
            }).then((isConfirm) => {
                try {
                    if (isConfirm.value) {
                        $.ajax({
                            method: 'post',
                            url: '/admin/menu/delete',
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
    });
</script>
