<?php
    // Breadcrumb
    $title = trans(config('admin.database.role_table') . '.title');
    $description = trans('admin.create');
    $breadcrumb = [$title];

    // Form
    $form = new \Oyhdd\Admin\Widget\Form\Form($model);

    $form->text('name')->required();
    $form->text('slug');
    // $form->treeSelect('permissions');

    $form->tools(function (\Oyhdd\Admin\Widget\Tools $tool) {
        $tool->showBack();
    });

?>

<!-- Breadcrumb -->
@include('layout.breadcrumb')

<!-- Form -->
@include('widget.form', ['form' => $form])
