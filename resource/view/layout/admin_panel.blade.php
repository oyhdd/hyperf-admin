<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <li class="fullpage-btn">
            <a href="javascript:void(0);" title="{{ trans('admin.fullscreen') }}">
                <i class="fa fa-arrows-alt"></i>
            </a>
        </li>

        <li title="{{ trans('admin.exit_fullscreen') }}" class="exit-fullpage-btn" style="display: none;">
            <a href="javascript:void(0);">
                <i class="fa fa-compress"></i>
            </a>
        </li>

        <li title="{{ trans('admin.refresh') }}">
            <a href="javascript:void(0);" class="container-refresh">
                <i class="fa fa-refresh"></i>
            </a>
        </li>

        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="hidden-xs">{{ $_data['user']->name }}</span>
                <img src="{{ $_data['user']->avatar ?: config('admin.default_avatar') }}" class="user-image">
            </a>
            <ul class="dropdown-menu">
                <li class="dropdown-user-item">
                    <a href="{{ admin_url('auth/setting') }}">
                        <i class="fa fa-user"></i>{{ trans('admin.setting') }}
                    </a>
                </li>
                <div class="divider"></div>
                <li class="dropdown-user-item">
                    <a href="{{ admin_url('auth/logout') }}" class="no-pjax">
                        <i class="fa fa-power-off"></i>{{ trans('admin.logout') }}
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="{{ admin_url('auth/lock') }}" title="{{ trans('admin.lockscreen') }}">
                <i class="fa fa-lock"></i>
            </a>
        </li>

    </ul>
</div>
