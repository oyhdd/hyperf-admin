<button type="submit" class="btn btn-danger grid-row-delete-all float-right">全部删除</button>
<br>

<script type="text/javascript">
    $(function () {
        // delete all items
        $('.grid-row-delete-all').unbind('click').click(function() {

            Swal.fire({
                type: 'warning', // 弹框类型
                title: '确认删除所有记录', // 标题
                // text: "删除后将无法恢复，请确认！", //显示内容

                confirmButtonColor: '#DD6B55',// 确定按钮的 颜色
                confirmButtonText: '确认',// 确定按钮的 文字
                showCancelButton: true, // 是否显示取消按钮
                cancelButtonText: "取消", // 取消按钮的 文字
            }).then((isConfirm) => {
                try {
                    if (isConfirm.value) {
                        $.ajax({
                            method: 'post',
                            url: window.location.pathname + '/delete-all',
                            data: {},
                            success: function (data) {
                                window.location.reload();
                            },
                            error: function(data) {
                                Swal.fire("Error", "请求失败，请稍后重试！", "error");
                            }
                        });
                    }
                } catch (e) {
                    Swal.fire("Error", "请求失败，请稍后重试！", "error");
                }
            });
        });
    })
</script>