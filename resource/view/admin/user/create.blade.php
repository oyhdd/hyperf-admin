<?php
    // Breadcrumb
    $title = trans(config('admin.database.user_table') . '.title');
    $description = trans('admin.create');
    $breadcrumb = [$title];

    // Form
    $form = new \Oyhdd\Admin\Widget\Form\Form($model);

    $form->text('username')->required();
    $form->text('name')->required();
    $form->text('avatar');
    $form->password('password')->required();
    $form->password('password_confirmation')->required();
    $form->multipleSelect('roles')->options(make(config('admin.database.role_model'))::all()->pluck('name', 'id')->toArray());

    $form->tools(function (\Oyhdd\Admin\Widget\Tools $tool) {
        $tool->showBack();
    })
?>

<!-- Breadcrumb -->
@include('layout.breadcrumb')

<!-- Form -->
@include('widget.form', ['form' => $form])
