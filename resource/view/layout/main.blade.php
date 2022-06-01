<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', config('translation.locale')) }}">

    @include('layout.head')

    <body class="hold-transition {{ $_data['site']['color_scheme'] ?? 'skin-black' }} sidebar-mini">
        <div class="wrapper">
            @include('layout.header')
            @include('layout.sidebar')

            <div class="content-wrapper" id="pjax-container">
                @include('layout.content')
            </div>

            @include('layout.footer')
        </div>
        @include('layout.js')
    </body>
</html>