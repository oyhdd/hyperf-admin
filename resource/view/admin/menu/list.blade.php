<?php

    $level = !empty($level) ? $level : 0;
?>

<style type="text/css">
    a.tree_branch_delete{ color:#d9534f}
</style>

<li style="margin-left: {{ 1 * $level }}rem;">
    <span>
        <i class="fas {{ $item['icon'] }}"></i>
    </span>
    <span class="text">{{ $item['title'] }}</span>&nbsp;&nbsp;
    <a href="{{ $item['uri'] }}">{{ $item['uri'] }}</a>
    <span class="float-right">
        <a href="/admin/menu/{{ $item['id'] }}/edit"><i class="fas fa-edit"></i></a>&nbsp;
        <a href="javascript:void(0);" data-id="{{ $item['id'] }}" class="tree_branch_delete"><i class="fas fa-trash"></i></a>
    </span>
</li>
@if (!empty($item['children']))
    @php
        $temp_level1 = $level;
        $level += 2;
    @endphp
    @foreach($item['children'] as $item)
        <li style="margin-left: {{ $level }}rem;">
            <span>
                <i class="fas {{ $item['icon'] }}"></i>
            </span>
            <span class="text">{{ $item['title'] }}</span>&nbsp;&nbsp;
            <a href="{{ $item['uri'] }}">{{ $item['uri'] }}</a>
            <span class="float-right">
                <a href="/admin/menu/{{ $item['id'] }}/edit"><i class="fas fa-edit"></i></a>&nbsp;
                <a href="javascript:void(0);" data-id="{{ $item['id'] }}" class="tree_branch_delete"><i class="fas fa-trash"></i></a>
            </span>
        </li>
        @if (!empty($item['children']))
            @php
                $temp_level2 = $level;
                $level += 2;
            @endphp
            @foreach($item['children'] as $item)
                @include('admin.menu.list', $item)
            @endforeach
            @php
                $level = $temp_level2;
            @endphp
        @endif
    @endforeach
    @php
        $level = $temp_level1;
    @endphp
@endif

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