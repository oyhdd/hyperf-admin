<?php
    $title = 'Dashboard';
    $description = '';
    $breadcrumb = [];
?>
@include('layout.breadcrumb')

Hello, {{$_data['user']->name}}, {{ date("Y-m-d H:i:s") }} <br> You are using blade template now.


