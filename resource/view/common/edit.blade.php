<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">{{ $form_title ?? (empty($model->id) ? trans('admin.create') : trans('admin.edit')) }}</h3>
                <div class="card-tools mr-0">
                    <!-- 工具框 -->
                    @yield('card-tools')
                </div>
            </div>
            <form role="form" class="form-horizontal" method="post" enctype="multipart/form-data" {{ isset($action) ? "action='{$action}'" : '' }}>
                <div class="card-body">
                    @foreach($model->formData as $column => $attribute)
                    @if(isset($attribute['label']) && isset($attribute['input']))
                    <div class="form-group row">
                        {!! $attribute['label'] !!}
                        <div class="input-group col-sm-7">
                            {!! $attribute['input'] !!}
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="card-footer">
                    <div class="col-sm-10">
                        <button type="submit" class="btn {{ empty($model->id) ? "btn-success" : "btn-primary" }} float-right">
                            {{ empty($model->id) ? trans('admin.create') : trans('admin.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('.duallistbox').bootstrapDualListbox()

        // delete single item
        $('.model-delete').unbind('click').click(function() {
            let url = "{{ str_replace('/edit', '/delete', $_path) }}"
            let list_url = "{{ str_replace('/'.$model->id.'/edit', '', $_path) }}"
            Swal.fire({
                type: 'warning', // 弹框类型
                title: '确认删除该记录(id = {{ $model->id }})', // 标题
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
                            url: url,
                            data: {},
                            success: function (data) {
                                window.location.href = list_url;
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