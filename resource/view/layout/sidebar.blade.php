<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ $_data['user']->avatar ?: config('admin.default_avatar') }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ $_data['user']->name }}</p>
                <i class="fa fa-circle text-success"></i>
                <span class="text-xs">{{ trans('admin.online') }}</span>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            @each('layout.menu', $_data['menu'], 'item')
        </ul>
    </section>
</aside>
