<?php

$title = '权限列表';
$description = 'show';
$breadcrumb[] = ['text' => $title, 'url' => str_replace("/create", '', $_path)];
$breadcrumb[] = ['text' => '创建'];

$form = new \Oyhdd\Admin\Model\Widget\Form($model);
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<!-- 表单创建 -->
@include('common.edit', [
    // 'action' => $_path,
    // 'form' => $form,
    'attributes' => [
        $form->text('name', '名称')->rules('required'),
        $form->text('slug', '标识')->rules('required'),
        $form->multipleSelect('http_method', '请求方式')->options($model->getHttpMethodsOptions(), explode(',', $model->http_method)),
        $form->textarea('http_path', '路径'),
    ]
])

