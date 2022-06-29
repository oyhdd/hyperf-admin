<?php
    // Breadcrumb
    $title = trans(config('admin.database.user_table') . '.title');
    $description = trans('admin.edit');
    $breadcrumb = [$title];

    // Form
    $form = new \Oyhdd\Admin\Widget\Form\Form($model);

    $form->text('username')->required();
    $form->text('name')->required();
    $form->text('avatar');
    $form->password('password')->required();
    $form->password('password_confirmation')->required();
?>

<!-- Breadcrumb -->
@include('layout.breadcrumb')

<!-- Form -->
@include('widget.form', ['form' => $form])
