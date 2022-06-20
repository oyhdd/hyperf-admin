@php
    $queryParams = [];
    $primaryKey = 'id';
    if (!empty($grid)) {
        $queryParams = $grid->getParams();
        $primaryKey = $grid->getKeyName();
    }
    $_sort = $queryParams['_sort'] ?? '';
    $_sort_type = $queryParams['_sort_type'] ?? 'asc';
    $show_sort_type = 'asc';
    if ($_sort_type === 'asc') {
        $show_sort_type = 'desc';
    }

    $queryParams['_ha_no_animation'] = 1;
@endphp
<style type="text/css">
    .sweet-alert.showSweetAlert p {
        word-wrap: break-word;
        word-break: break-all;
        overflow-x: hidden;
        max-height: 200px;
        overflow-y: scroll;
    }
</style>
<table @if(!empty($id)) id="table_{{ $id }}" @endif class="table table-hover" style="min-width: 1000px;table-layout: auto;">
    <tbody>
        <tr>
            @if (!empty($grid))
                <th class="text-center">
                    <input type="checkbox" class="grid-select-all" style="position: absolute; opacity: 0;">
                </th>
            @endif
            @foreach($header as $key => $item)
                <th @if (!empty($item['width'])) style="width: {{ $item['width'] }}px;" @endif>
                    {{ $item['title'] }}
                    @isset($item['sort'])
                        @if (empty($_sort))
                            @if (!empty($item['sort']))
                                @php
                                    $show_sort_type = 'asc';
                                    if ($item['sort'] === 'asc') {
                                        $show_sort_type = 'desc';
                                    }
                                @endphp
                                <a class="fa fa-fw fa-sort-amount-{{ strtolower($item['sort']) }}" href="?_sort={{ $key }}&_sort_type={{ $show_sort_type }}&_ha_no_animation=1"></a>
                            @else
                                <a class="fa fa-fw fa-sort" href="?_sort={{ $key }}&_sort_type=desc&_ha_no_animation=1"></a>
                            @endif
                        @else
                            <a class="fa fa-fw {{ $_sort == $key ? ('fa-sort-amount-' . strtolower($_sort_type)) : 'fa-sort' }}" href="?_sort={{ $key }}&_sort_type={{ $_sort == $key ? $show_sort_type : 'desc' }}&_ha_no_animation=1"></a>
                        @endif
                    @endisset
                </th>
            @endforeach
            @if (!empty($grid) && $grid->showActions())
                <th>
                    {{ trans('admin.action') }}
                </th>
            @endif
        </tr>
        @foreach($body as $item)
        <tr>
            @if (!empty($grid))
                <td class="text-center">
                    <input type="checkbox" class="grid-row-checkbox" style="position: absolute; opacity: 0;" data-id="{{ $item[$primaryKey] ?? 0 }}">
                </td>
            @endif
            @foreach($header as $key => $value)
                <td>
                    <?php
                        if (isset($value['callback'])) {
                            $item[$key] = $value['callback']->call($item);
                        }
                        if (isset($value['label'])) {
                            $item[$key] = collect($item[$key])->map(function ($val) use($value) {
                                return "<span class='label label-{$value['label']}'>{$val}</span>";
                            })->implode(' ');
                        }
                        if (isset($value['link'])) {
                            $href = $value['link']->call($item);
                            $item[$key] = "<a href='{$href}'>{$item[$key]}</a>";
                        }
                    ?>
                    {!! $item[$key] !!}
                </td>
            @endforeach
            @if (!empty($grid) && $grid->showActions())
                <td class="grid-actions">

                    @php $grid->getActionsCallback()->call($item, $grid->getActions()); @endphp

                    {!! $grid->getActions()->renderPrepend() !!}

                    @if ($grid->getActions()->showView())
                        <a href="{{ $path }}/{{ $item[$grid->getKeyName()] }}" title="{{ trans('admin.show') }}"><i class="fa fa-eye"></i></a>
                    @endif

                    @if ($grid->getActions()->showEdit())
                        <a href="{{ $path }}/{{ $item[$grid->getKeyName()] }}/edit" title="{{ trans('admin.edit') }}"><i class="fa fa-edit"></i></a>
                    @endif

                    @if ($grid->getActions()->showDelete())
                        <a href="javascript:void(0);" class="grid-row-delete" data-id="{{ $item[$grid->getKeyName()] }}" title="{{ trans('admin.delete') }}"><i class="fa fa-trash"></i></a>
                    @endif

                    {!! $grid->getActions()->renderAppend() !!}
                </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    let table_id = '{{ $id }}';

    window.selectedRows = function (table_id) {
        let selected = [];
        $('#table_' + table_id + ' .grid-row-checkbox:checked').each(function () {
            selected.push($(this).data('id'));
        });

        return selected;
    };

    $(function () {
        $('#table_' + table_id +' .grid-select-all').iCheck({checkboxClass: 'icheckbox_minimal-blue'}).on('ifChanged', function (event) {
            if (this.checked) {
                $('.grid-row-checkbox').iCheck('check');
            } else {
                $('.grid-row-checkbox').iCheck('uncheck');
            }

            renderOption(table_id);
        });

        $('#table_' + table_id + ' .grid-row-checkbox').iCheck({checkboxClass: 'icheckbox_minimal-blue'}).on('ifChanged', function () {
            if (this.checked) {
                $(this).closest('tr').css('background-color', '#ffffd5');
            } else {
                $(this).closest('tr').css('background-color', '');
            }

            renderOption(table_id);
        });

        $('.option-' + table_id + ' .grid-batch-delete').click(function () {
            deleteData(selectedRows(table_id).join())
        })

        function renderOption(table_id) {
            if (selectedRows(table_id).length) {
                $('.option-' + table_id).show();
            } else {
                $('.option-' + table_id).hide();
            }
        }

        $('#table_' + table_id + ' .grid-row-delete').click(function () {
            deleteData($(this).data('id'))
        });

        function deleteData(id) {
            swal({
                title: '{{ trans("admin.delete_confirm") }}',
                text: 'ID : ' + id,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: '{{ trans("admin.confirm") }}',
                closeOnConfirm: true,
                cancelButtonText: '{{ trans("admin.cancel") }}',
            },
            function () {
                $.ajax({
                    method: 'post',
                    url: '{{ $path }}/delete',
                    data: {
                        id: id
                    },
                    success: function (data) {
                        $.pjax.reload('#pjax-container');
                    },
                    error: function (data) {
                        if (data.responseText !== '') {
                            toastr.error(data.responseText);
                        } else {
                            toastr.error('{{ trans("admin.error") }}');
                        }
                    },
                });
            });
        };

        $('.option-' + table_id + ' .grid-batch-export').click(function () {
            exportData(selectedRows(table_id).join())
        });

        $('.grid-tool-' + table_id + ' .export-current').click(function () {
            exportAll(0);
        });

        $('.grid-tool-' + table_id + ' .export-all').click(function () {
            exportAll(1);
        });

        function exportData(id) {
            let form = $("<form>");
            form.attr("style", "display:none");
            form.attr("target", "");
            form.attr("method", "post");
            form.attr("action", '{{ $path }}/export' + '?_perPage={{ $per_page }}&_page={{ $current_page }}');
            let input1 = $("<input>");
            input1.attr("type", "hidden");
            input1.attr("name", '{{ $primaryKey }}');
            input1.attr("value", id);
            $("body").append(form);
            form.append(input1);
            form.submit();
            form.remove();
            return false;
        };

        function exportAll(isAll) {
            let form = $("<form>");
            form.attr("style", "display:none");
            form.attr("target", "");
            form.attr("method", "post");
            form.attr("action", '{{ $path }}/export' + '?_perPage={{ $per_page }}&_page={{ $current_page }}');
            let input1 = $("<input>");
            input1.attr("type", "hidden");
            input1.attr("name", "is_all");
            input1.attr("value", Number(isAll));
            let input2 = $("<input>");
            input2.attr("type", "hidden");
            input2.attr("name", "is_page");
            input2.attr("value", Number(!isAll));
            $("body").append(form);
            form.append(input1);
            form.append(input2);
            form.submit();
            form.remove();
            return false;
        };
    });
</script>