<?php
    // Breadcrumb
    $title = trans(config('admin.database.role_table') . '.title');
    $description = trans('admin.edit');
    $breadcrumb = [$title];

    // Form
    $form = new \Oyhdd\Admin\Widget\Form\Form($model);

    $form->display('id');
    $form->text('name')->required();
    $form->text('slug')->required();
    $form->tree('permissions')->nodes(function () {
        return make(config('admin.database.permission_model'))->query()->get();
     })->expand();
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
