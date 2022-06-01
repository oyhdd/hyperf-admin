@php
    $query = $grid->getParams();
    unset($query['_page'], $query['_pjax']);
    $query['_ha_no_animation'] = 1;
    $queryStr = http_build_query($query);
    unset($query['_perPage']);
    $pageQueryStr = http_build_query($query);
@endphp
<span style="line-height: 30px;">
    {!! trans('admin.pagination.range', ['from' => $from, 'to' => $to, 'total' => $total]) !!}&nbsp;&nbsp;&nbsp;<b>query time: </b>{{ $query_time }}ms
</span>
<ul class="pagination pagination-sm no-margin pull-right">
    <!-- Previous Page Link -->
    @if ($current_page <= 1)
    <li class="page-item disabled">
        <span class="page-link">«</span>
    </li>
    @else
    <li class="page-item"><a class="page-link" href="{{ $prev_page_url }}&{{ $queryStr }}" rel="prev">«</a></li>
    @endif

    @if(! empty($elements))
    @foreach ($elements as $element)
        <!-- "Three Dots" Separator -->
        @if (is_string($element))
        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
        @endif

        <!-- Array Of Links -->
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $current_page)
                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach
    @endif

    <!-- Next Page Link -->
    @if ($current_page < $last_page)
    <li class="page-item"><a class="page-link" href="{{ $next_page_url }}&{{ $queryStr }}" rel="next">»</a></li>
    @else
    <li class="page-item disabled"><span class="page-link">»</span></li>
    @endif
</ul>

<label class="control-label pull-right" style="margin: 0 10px 0 0; font-weight: 100;">
    <select class="input-sm grid-per-pager" name="per-page">
    @foreach ($perPageList as $perPage)
        <option value="{{ $path }}?_perPage={{ $perPage }}&{{ $pageQueryStr }}" {{ ($per_page == $perPage) ? 'selected' : '' }}>
           {{ $perPage }}
        </option>
    @endforeach
</label>

<script>
    $(function () {
        $('.grid-per-pager').on('change', function () {
            $.pjax({url: this.value, container: '#pjax-container'});
        });
    });
</script>
