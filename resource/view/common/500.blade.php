<!DOCTYPE html>
<html lang="{{ config('translation.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ $data['_csrf_token'] }}">
    <title>{{config('admin.title')}} | Error </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/vendor/hyperf-admin/AdminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/vendor/hyperf-admin/AdminLTE/plugins/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/vendor/hyperf-admin/AdminLTE/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition">
    <div class="wrapper" style="margin-top: 10%;">
        <!-- Main content -->
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-danger">500</h2>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong.</h3>
                    <p>
                        {{ $data['error'] }}
                    </p>
                    <p>
                        We will work on fixing that right away.
                        Meanwhile, you may return to <a href="/admin">Home Page</a>
                    </p>
                </div>
            </div>
            <!-- /.error-page -->
        </section>
    </div>
    <!-- ./wrapper -->
    <!-- jQuery -->
    <script src="/vendor/hyperf-admin/AdminLTE/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/vendor/hyperf-admin/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/vendor/hyperf-admin/AdminLTE/dist/js/adminlte.min.js"></script>
</body>

</html>