<?php
    // Breadcrumb
    $title = trans(config('admin.database.user_table') . '.title');
    $description = trans('admin.list');
    $breadcrumb = [$title];

    // Grid
    $grid = new \Oyhdd\Admin\Widget\Grid\Grid($model, $dataProvider);

    $grid->column('id')->sortable();
    $grid->column('username');
    $grid->column('name');
    $grid->column('roles')->display(function() {
        return $this->roles->pluck('name');
    })->label();
    $grid->column('created_at');
    $grid->column('updated_at');

    $grid->actions(function (\Oyhdd\Admin\Widget\Grid\Actions $action) {
        // $action->disableView()->disableEdit()->disableDelete();
    });

    $grid->filter(function (\Oyhdd\Admin\Widget\Grid\Filter $filter) {
        $filter->text('id');
        $filter->text('username');
        $filter->text('name');
    });
?>

<!-- Breadcrumb -->
@include('layout.breadcrumb')

<!-- Table -->
@include('widget.grid', ['grid' => $grid])
