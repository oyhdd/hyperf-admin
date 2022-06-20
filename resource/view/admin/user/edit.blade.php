<?php
    // Breadcrumb
    $title = trans(config('admin.database.user_table') . '.title');
    $description = trans('admin.edit');
    $breadcrumb = [$title];

    // Form
    $form = new \Oyhdd\Admin\Widget\Form\Form($model);

    $form->display('id');
    $form->text('username')->required();
    $form->text('name')->required();
    $form->text('avatar');
    $form->password('password');
    $form->password('password_confirmation');
    $form->multipleSelect('roles')->options(
        make(config('admin.database.role_model'))::all()->pluck('name', 'id')->toArray(),
        array_column($model->roles->toArray(), 'id'),
    );
    $form->display('created_at');
    $form->display('updated_at');

    $form->tools(function (\Oyhdd\Admin\Widget\Tools $tool) {
        $tool->showBack();
    })
?>

<!-- Breadcrumb -->
@include('layout.breadcrumb')

<!-- Form -->
@include('widget.form', ['form' => $form])
