<?php

    $tableId = uniqid('grid_table');
    parse_str(parse_url($_full_path)['query'] ?? '', $pathQuery);
    $path_query = $pathQuery;
    unset($path_query['_sort']);
    $url_without_sort = http_build_query($path_query);
    $path_query = $pathQuery;
?>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header" style="padding: 0.95rem 1.25rem;">
                @if(empty($searchModel))
                    <h3 class="card-title">{{ $table_title ?? trans('admin.list') }}</h3>
                @else
                    <button type="button" class="btn btn-primary " data-toggle="collapse" data-target="#{{ $tableId }}_search_tools"><i class="fa fa-filter"></i> {{ trans('admin.filter') }}</button>
                @endif
                <div class="card-tools mr-0">
                    <!-- card tools -->
                    @yield('card-tools')
                </div>
            </div>
            <div class="card-body table-responsive">
                <div id="{{ $tableId }}_search_tools" class="collapse {{ (!empty($searchModel) && $searchModel->getExpandFilter()) ? 'show' : '' }}">
                    <!-- grid search form -->
                    <form class="form-horizontal" method="get">
                        {!! !empty($searchModel) ? $searchModel->render() : '' !!}
                        <div class="row card-footer m-auto">
                            <button type="submit" class="btn btn-primary btn-xs mr-2"><i class="fa fa-search"></i> {{ trans('admin.search') }}</button>
                            <a href="{{ $_path }}" class="btn btn-default btn-xs"><i class="fa fa-undo"></i> {{ trans('admin.reset') }}</a>
                        </div>
                    </form>
                </div>

                <table id="{{ $tableId }}" class="table admin-table table-bordered table-striped">
                    <thead>
                        <tr>
                        @foreach($columns as $column)
                            @php
                            if (!empty($column['sort'])) {
                                $data_sort = $column['sort'];
                                $sorting = 'sorting';
                            } elseif (!empty($column['multi_sort'])) {
                                $data_sort = $column['multi_sort'];
                                $sorting = 'sorting multi-sort';
                            } else {
                                $data_sort = '';
                                $sorting = '';
                            }
                            if (!empty($sorting) && isset($pathQuery['_sort']) && isset($pathQuery['_sort'][$data_sort])) {
                                $sorting .= " sorting_{$pathQuery['_sort'][$data_sort]}";
                            }
                            @endphp
                            <th data-sort="{{ $data_sort }}" class="{{ $sorting }}" style="{{ !empty($column['width']) ? "width: {$column['width']}px !important;" : "" }}">
                                {{ $column['label'] ?? ucfirst($column['attribute'] ?? $column) }}
                            </th>
                        @endforeach
                        @if (!empty($action))
                            <th style="min-width: 70px !important;">{{ trans("admin.action") }}</th>
                        @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($dataProvider->items() as $item)
                        <tr>
                        @foreach($columns as $column)
                            @if (isset($column['value']))
                                <td style="{{ $column['style'] ?? '' }}">{!! $column['value']($item) !!}</td>
                            @else
                                <td style="{{ $column['style'] ?? '' }}">{{ $item->{$column['attribute'] ?? $column} ?? '' }}</td>
                            @endif
                        @endforeach
                        @if (!empty($action))
                            <td>
                                @foreach($action as $op)
                                    @if ($op == 'view')
                                        <a href="{{ $_path }}/{{ $item->getKey() }}" class="text-success" title='{{ trans('admin.view') }}'><i class="fas fa-eye"></i></a>
                                    @elseif ($op == 'edit')
                                        <a href="{{ $_path }}/{{ $item->getKey() }}/edit" class="text-primary" title='{{ trans('admin.edit') }}'><i class="fas fa-edit"></i></a>
                                    @elseif ($op == 'delete')
                                        <a href="javascript:void(0);" data-id="{{ $item->getKey() }}" class="text-danger grid-row-delete" title='{{ trans('admin.delete') }}'><i class="fas fa-trash"></i></a>
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

                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info pt-3" style="white-space: nowrap;" id="{{$tableId}}_info" role="status" aria-live="polite">
                            @if(!empty($dataProvider->items()))
                                ??? {{ $dataProvider->firstItem() }} ??? {{ $dataProvider->lastItem() }} ????????? {{ $dataProvider->total() }} ?????????
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <!-- paginate -->
                        @include('common.tool.paginate')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    let sort = {
        single: {},
        multi: {}
    }
    $(function () {
        $.dataTablesSettings = {
            serverSide: false,
            retrieve: true,
            paging: false, // ??????
            lengthChange: false, // ??????????????????
            searching: false, // ??????
            ordering: false, // ??????
            info: false, // ???????????????
            autoWidth: true,
            // responsive: true,
            processing: false,
            language: {
                zeroRecords: "????????????",
            }
        }
        $("#{{ $tableId }}").DataTable($.dataTablesSettings)
    })

    // ??????????????????
    $('.grid-row-delete').unbind('click').click(function() {
        var id = $(this).data('id');

        Swal.fire({
            type: 'warning', // ????????????
            title: '??????????????????????', // ??????
            // text: "???????????????????????????????????????", //????????????

            confirmButtonColor: '#DD6B55',// ??????????????? ??????
            confirmButtonText: '??????',// ??????????????? ??????
            showCancelButton: true, // ????????????????????????
            cancelButtonText: "??????", // ??????????????? ??????
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
                            Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000,
                            }).fire({
                                type: "error",
                                title: data.responseText,
                            })
                        }
                    });
                }
            } catch (e) {
                Swal.fire("Error", "?????????????????????????????????", "error");
            }
        });
    })

    // ??????
    $('.sorting').click(function() {
        if (!$(this).hasClass('sorting_asc') && !$(this).hasClass('sorting_desc')) {
            $(this).addClass('sorting_asc')
        } else {
            $(this).toggleClass('sorting_asc')
            $(this).toggleClass('sorting_desc')
        }
        let order = 'asc'
        if ($(this).hasClass('sorting_desc')) {
            order = 'desc'
        }

        let order_type = 'multi' // ????????????
        if (!$(this).hasClass('multi-sort')) { // ????????????
            order_type = 'single'
            sort[order_type] = {}
        }
        sort[order_type][$(this).data("sort")] = order

        // ?????????????????????????????????
        let path_query = JSON.parse('{!! json_encode($path_query) !!}')
        if (!path_query.hasOwnProperty('_sort') || order_type == 'single') {
            path_query['_sort'] = {}
        }

        let query = ''
        for (let column in sort[order_type]) {
            path_query['_sort'][column] = sort[order_type][column]
        }

        for (let i in path_query['_sort']) {
            var is_multi = $('thead').find('th[data-sort="'+i+'"]').hasClass("multi-sort")
            if (order_type == 'multi' && !is_multi) {
                continue // ?????????????????????????????????
            }
            if (query == '') {
                query += "_sort["+ i +"]=" + path_query['_sort'][i]
            } else {
                query += "&_sort["+ i +"]=" + path_query['_sort'][i]
            }
        }
        query += '{!! empty($url_without_sort) ? "" : "&".$url_without_sort !!}'

        window.location = window.location.pathname + "?" + query
    });
</script>