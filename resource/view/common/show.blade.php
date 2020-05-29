<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">{{ $form_title ?? trans('admin.show') }}</h3>
                <div class="card-tools  mr-0">
                    <!-- 工具框 -->
                    @yield('card-tools')
                </div>
                <!-- /.card-tools -->
            </div>
            <div class="card-body">
                @foreach($attributes as $attribute)
                <div class="form-group row">
                    <label class="control-label col-form-label text-right col-sm-3">{{ $attribute['label'] ?? $attribute }}</label>
                    <div class="input-group col-sm-7">
                        @if (isset($attribute['value']))
                        <pre type="text" class="form-control" style="height: auto;">{!! $attribute['value']($model) !!}</pre>
                        @else
                        <input type="text" class="form-control bg-white" disabled="disabled" value="{{ $model->{$attribute['attribute'] ?? $attribute} ?? '' }}">
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        // delete single item
        $('.model-delete').unbind('click').click(function() {
            let url = '{{ $_path }}' + '/delete'
            let list_url = '{{ str_replace('/'.$model->id, '', $_path) }}'
            Swal.fire({
                type: 'warning', // 弹框类型
                title: '确认删除该记录(id = {{ $model->id }})', // 标题
                // text: "删除后将无法恢复，请确认！", //显示内容

                confirmButtonColor: '#DD6B55',// 确定按钮的 颜色
                confirmButtonText: '确认',// 确定按钮的 文字
                showCancelButton: true, // 是否显示取消按钮
                cancelButtonText: '取消', // 取消按钮的 文字
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
                                Swal.fire('Error', '请求失败，请稍后重试！', 'error');
                            }
                        });
                    }
                } catch (e) {
                    Swal.fire('Error', '请求失败，请稍后重试！', 'error');
                }
            });
        });
    })
</script>