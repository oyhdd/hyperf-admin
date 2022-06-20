<?php
    // Breadcrumb
    $title = trans(config('admin.database.role_table') . '.title');
    $description = trans('admin.list');
    $breadcrumb = [$title];

    // Grid
    $grid = new \Oyhdd\Admin\Widget\Grid\Grid($model, $dataProvider);

    $grid->column('id')->sortable();
    $grid->column('name');
    $grid->column('slug')->label();
    $grid->column('permissions')->display(function() {
        return $this->permissions->pluck('name');
    })->label();
    $grid->column('created_at');
    $grid->column('updated_at');

    $grid->actions(function (\Oyhdd\Admin\Widget\Grid\Actions $action) {
        // $action->disableView()->disableEdit()->disableDelete();
    });

    $grid->filter(function (\Oyhdd\Admin\Widget\Grid\Filter $filter) {
        $filter->text('id');
        $filter->text('name');
    });
?>

<!-- Breadcrumb -->
@include('layout.breadcrumb')

<!-- Table -->
@include('widget.grid', ['grid' => $grid])
