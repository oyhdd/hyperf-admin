@php
    /**
     * $current_page, $data, $first_page_url, $from, $last_page, $last_page_url, $next_page_url, $path, $per_page, $prev_page_url, $to, $total, $elements, $perPageList, $query_time
     */
    extract($grid->getData());
@endphp

<div class="box box-{{ $grid->style() }}">
    <div class="box-header with-border grid-tool-{{ $grid->getElementId() }}">
        <div class="pull-right">
            <div class="btn-group" style="margin-right: 5px">
                <a href="{{ $path }}/create" class="btn btn-sm btn-primary btn-outline">
                    <i class="fa fa-plus"></i>&nbsp;&nbsp;{{ trans('admin.new') }}
                </a>
            </div>
            <div class="btn-group" style="margin-right: 5px">
                    <a class="btn btn-sm btn-default btn-outline">{{ trans('admin.export') }}</a>
                    <button type="button" class="btn btn-sm btn-default btn-outline dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="javascript:void(0);" class="export-current">{{ trans('admin.current_page') }}</a></li>
                        <li><a href="javascript:void(0);" class="export-all">{{ trans('admin.all') }}</a></li>
                    </ul>
            </div>
            {!! $grid->renderTools() !!}
        </div>
        <span>
            <div class="btn-group option-{{ $grid->getElementId() }}" style="margin-right: 5px; display: none;">
                <a class="btn btn-sm btn-default btn-outline">{{ trans('admin.option') }}</a>
                <button type="button" class="btn btn-sm btn-default btn-outline dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:void(0);" class="grid-batch-delete">{{ trans('admin.delete') }}</a></li>
                    <li><a href="javascript:void(0);" class="grid-batch-export">{{ trans('admin.export') }}</a></li>
                </ul>
            </div>
            @if ($grid->showFilter())
            <a href="javascript:;" class="btn btn-sm btn-primary btn-outline" id="filter_{{ $grid->getElementId() }}" style="margin-right: 5px">
                <i class="fa fa-filter"></i>&nbsp;&nbsp;{{ trans('admin.filter') }}
            </a>
            @endif
            <a class="btn btn-sm btn-primary btn-outline grid-refresh">
                <i class="fa fa-refresh"></i>&nbsp;&nbsp;{{ trans('admin.refresh') }}
            </a>
        </span>
        <script>
            $('.grid-tool-{{ $grid->getElementId() }} .grid-refresh').unbind('click').on('click', function () {
                $.pjax.reload('#pjax-container');
                toastr.success('{{ trans("admin.refresh_succeeded") }}');
            });
            $("#filter_{{ $grid->getElementId() }}").click(function () {
                $('.filter-area-{{ $grid->getElementId() }}').toggle();
            });
        </script>
    </div>
    <!-- /.box-header -->

    <div class="box-header with-border filter-area-{{ $grid->getElementId() }}" style="display: none;">
        @include('widget.form.form', ['form' => $grid->getFilter()])
    </div>
    <!-- /.box-header -->

    <div class="box-body" style="overflow-x: scroll;overflow-y: hidden;padding:0;">
        @include('widget.grid.table', [
            'id' => $grid->getElementId(),
            'header' => $grid->getColumns(),
            'body' => $data,
        ])
    </div>
    <!-- /.box-body -->

    <div class="box-footer clearfix">
        @include('widget.grid.paginator')
    </div>
    <!-- /.box-footer -->
</div>


