<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="padding: 0.95rem 1.25rem;">
                <h3 class="card-title">{{ $table_title ?? trans('admin.list') }}</h3>
                <div class="card-tools mr-0">
                    <!-- 工具框 -->
                    @yield('card-tools')
                </div>
                <!-- /.card-tools -->
            </div>
            <div class="card-body">
                <table class="table admin-table table-bordered table-striped">
                    <thead>
                        <tr>
                        @foreach($columns as $column)
                            @if (!empty($column['width']))
                            <th style="width: {{ $column['width'] }}px !important;">{{ $column['label'] ?? ucfirst($column['attribute'] ?? $column) }}</th>
                            @else
                            <th>{{ $column['label'] ?? ucfirst($column['attribute'] ?? $column) }}</th>
                            @endif
                        @endforeach
                        @if (!empty($action))
                            <th style="width: 60px;">操作</th>
                        @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($dataProvider as $item)
                        <tr>
                        @foreach($columns as $column)
                            @if (isset($column['value']))
                            <td>{!! $column['value']($item) !!}</td>
                            @else
                            <td>{{ $item->{$column['attribute'] ?? $column} ?? '' }}</td>
                            @endif
                        @endforeach
                        @if (!empty($action))
                            <td>
                                @foreach($action as $op)
                                    @if ($op == 'view')
                                    <a href="{{ $_path }}/{{ $item['id'] }}" class="text-success" title='查看'><i class="fas fa-eye"></i></a>
                                    @elseif ($op == 'edit')
                                    <a href="{{ $_path }}/{{ $item['id'] }}/edit" class="text-primary" title='编辑'><i class="fas fa-edit"></i></a>
                                    @elseif ($op == 'delete')
                                    <a href="javascript:void(0);" data-id="{{ $item['id'] }}" class="text-danger grid-row-delete" title='删除'><i class="fas fa-trash"></i></a>
                                    @else
                                    {!! $op($item) !!}
                                    @endif
                                    &nbsp;&nbsp;
                                @endforeach
                            </td>
                        @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $.dataTablesSettings = {
            "retrieve": true,
            "paging": true, // 分页
            "lengthChange": false, // 改变每页条数
            "searching": false, // 搜索
            "ordering": true, // 排序
            "aaSorting": [], // 禁用初始排序
            "info": true, // 当前页信息
            "autoWidth": true,
            // "responsive": true,
            "processing": true,
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 75, 100, 200],
            "language": {
                lengthMenu: "显示 _MENU_ 条记录",
                zeroRecords: "暂无数据",
                info: "显示第 _START_ 至 _END_ 条，共 _TOTAL_ 条记录",
                infoEmpty: "暂无数据",
                InfoFiltered: "(从 _MAX_ 条数据中检索)",
                search: "搜索：",
                paginate: {
                    first: "首页",
                    previous: "上一页",
                    next: "下一页",
                    last: "最后一页"
                },
                processing: "<div id='status'><i class='fa fa-spinner fa-spin'></i></div>"
            }
        }
        // $(".admin-table").DataTable($.dataTablesSettings);

        // delete single item
        $('.grid-row-delete').unbind('click').click(function() {
            var id = $(this).data('id');

            Swal.fire({
                type: 'warning', // 弹框类型
                title: '确认删除该记录(id = '+id+')', // 标题
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
                            url: window.location.pathname + '/' + id +'/delete',
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