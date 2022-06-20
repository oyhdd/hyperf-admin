<?php
    // Breadcrumb
    $title = trans(config('admin.database.user_table') . '.title');
    $description = trans('admin.detail');
    $breadcrumb = [$title];

    // Show
    $show = new \Oyhdd\Admin\Widget\Show\Show($model);

    $show->field('id');
    $show->field('username');
    $show->field('name');
    $show->field('avatar');
    $show->field('roles')->as(function () {
        return $this->roles->pluck('name');
    })->label();
    $show->field('created_at');
    $show->field('updated_at');

    $show->tools(function (\Oyhdd\Admin\Widget\Tools $tool) {
        $tool->showBack();
    })
?>

<!-- Breadcrumb -->
@include('layout.breadcrumb')

<!-- Show -->
@include('widget.show', ['show' => $show])
