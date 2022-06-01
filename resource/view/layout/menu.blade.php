@if (empty($item['children']))
    <li class="{{ $item['active'] ? 'active' : '' }}">
        <a data-id="{{ $item['id'] ?? '' }}" href="{{ admin_url($item['uri']) }}" @if(mb_strpos($item['uri'], '://') !== false) target="_blank" @endif>
            <i class="fa {{ $item['icon'] }}"></i>
            <span> {{ trans("admin_menu.fields.{$item['title']}") }}</span>
        </a>
    </li>
@else
    <li class="treeview {{ $item['active'] ? 'active' : '' }}">
        <a href="#">
            <i class="fa {{ $item['icon'] }}"></i>
            <span>{{ trans("admin_menu.fields.{$item['title']}") }}</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @foreach($item['children'] as $item)
                @include('layout.menu', $item)
            @endforeach
        </ul>
    </li>
@endif