<?php
    // Breadcrumb
    $title = trans('admin_site.title');
    $description = trans('admin.edit');
    $breadcrumb = [$title];

    // Form
    $attributes = $model->getAll();
    foreach ($attributes as $key => $value) {
        $model->{$key} = $value;
    }
    $form = new \Oyhdd\Admin\Widget\Form\Form($model);
    $form->select('color_scheme')->options($model::$colorScheme)->default($model->color_scheme);
    $form->select('animation_type')->options($model::$animationType)->default($model->animation_type)->help('see more: <a href="https://daneden.github.io/animate.css/">https://daneden.github.io/animate.css/</a>');
    $form->number('animation_duration')->min(0);
    $form->number('animation_delay')->min(0);
    $form->number('toastr_timeout')->min(0);
    $form->switch('operation_log_off');
    $form->switch('allow_del_operation_log');

    $form->tools(function (\Oyhdd\Admin\Widget\Tools $tool) {
        $tool->showCollapse()->showRemove();
    })
?>

<!-- Breadcrumb -->
@include('layout.breadcrumb')

<!-- Form -->
@include('widget.form', ['form' => $form])
