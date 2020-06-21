<?php

    $title = '菜单';
    $description = '列表';
    $breadcrumb[] = ['text' => $title, 'url' => '/admin'];
?>

@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ trans('admin.menu') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="/admin/menu" data-source-selector="#refresh_menu">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="todo-list" data-widget="todo-list">
                        @each('admin.menu.list', $data['_menu'], 'item')
                    </ul>
                </div>
            </div>
            <div class="d-none" id="refresh_menu">
                <ul class="todo-list" data-widget="todo-list">
                    @each('admin.menu.list', $data['_menu'], 'item')
                </ul>
            </div>
        </div>
        <div class="col-lg-6">
            @include('admin.menu.create')
        </div>
    </div>
</div>
