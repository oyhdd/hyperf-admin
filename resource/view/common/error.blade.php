<?php

    $title = 'Error';
    $description = $code;
    $breadcrumb[] = ['text' => 'Error'];
?>

@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<div class="error-page">
    <h2 class="headline text-danger">{{ $data['code'] ?? 500 }}</h2>
    <div class="error-content">
        <h4><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong.</h4>
        <p class="text-center text-danger">
            {{ $data['error'] }}
        </p>
        <p>
            We will work on fixing that right away.
            Meanwhile, you may return to
        </p>
        <p class="text-center">
            <a class="btn btn-primary btn-xs mb-1 mr-2" href="/admin">Home Page</a>
            <a class="btn btn-warning btn-xs mb-1" href="javascript:history.back(-1)">Previous Page</a>
        </p>
    </div>
</div>
