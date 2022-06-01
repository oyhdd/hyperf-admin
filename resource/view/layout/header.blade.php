<header class="main-header">
    <!-- Logo -->
    <a href="{{ admin_url() }}" class="logo">
        <span class="logo-mini">{!! config('admin.logo_mini') !!}</span>
        <span class="logo-lg">{!! config('admin.logo') !!}</span>
    </a>
    <nav class="navbar navbar-static-top">
        <div id="firstnav">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div style="float: left;">
                <ul class="nav navbar-nav">
                    <li class="navbar-nav-btn-left" style="display: none;">
                        <a href="javascript:;" style="border-left: none;border-right: solid 1px #dedede;">
                            <i class="fa fa-angle-double-left"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="nav-tabs-content">
                <ul class="nav nav-tabs nav-addtabs">
                </ul>
            </div>
            <div style="float: left;">
                <ul class="nav navbar-nav">
                    <li class="navbar-nav-btn-right" style="display: none;">
                        <a href="javascript:;" style="border-left: solid 1px #dedede;border-right: none;">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </li>
                </ul>
            </div>

            @include('layout.admin_panel')
        </div>
    </nav>
</header>
