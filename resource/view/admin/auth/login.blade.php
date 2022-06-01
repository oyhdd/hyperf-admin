<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', config('translation.locale')) }}">

    @include('layout.head')

    <body class="hold-transition login-page" @if(config('admin.login_background_image'))style="background: url({{config('admin.login_background_image')}}) no-repeat;background-size: cover;"@endif>
        <div class="box box-default login-box">
            <div class="box-header with-border text-center">
                <h2><b>{!! config('admin.name') !!}</b></h2>
            </div>
            <form method="post">
                <div class="box-body">
                    <div class="form-group col-sm-12">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="{{ trans('admin_user.fields.username') }}" name="username" required>
                            <div class="input-group-addon">
                                <div class="input-group-text">
                                    <span class="fa fa-user"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="{{ trans('admin_user.fields.password') }}" name="password" required>
                            <div class="input-group-addon">
                                <div class="input-group-text">
                                    <span class="fa fa-lock"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="checkbox form-group col-sm-12 ">
                        <label>
                            <input type="checkbox" id="remember" name="remember" value="1">{{ trans('admin_user.fields.remember_me') }}
                        </label>
                        <button type="submit" class="btn btn-sm btn-primary pull-right">{{ trans('admin.login') }}</button>
                    </div>
                </div>
            </form>
          </div>

        @include('layout.js')
    </body>
</html>