@php
    $level = !empty($level) ? $level : 0;
@endphp

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
