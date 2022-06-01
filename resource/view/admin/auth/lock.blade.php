<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', config('translation.locale')) }}">

    @include('layout.head')

    <body class="hold-transition lockscreen" @if(config('admin.lockscreen.background_image'))style="background: url({{config('admin.lockscreen.background_image')}}) no-repeat;background-size: cover;"@endif>
        <div class="lockscreen-wrapper">

            <div class="lockscreen-name text-white text-lg">{{ admin_user()->name ?? '' }}</div>

            <div class="lockscreen-item">
                <div class="lockscreen-image">
                    <img src="{{ admin_user()->avatar ?? config('admin.default_avatar') }}" alt="User Image">
                </div>

                <form class="lockscreen-credentials" action="{{ admin_url('auth/unlock') }}" method="post">
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="{{ trans('admin_user.fields.password') }}" name="password" required>
                        <div class="input-group-btn">
                            <button type="submit" class="btn">
                                <i class="fa fa-arrow-right text-muted"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('layout.js')
    </body>
</html>