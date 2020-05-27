<?php

use Oyhdd\Admin\Model\{AdminMenu, AdminRole, AdminPermission};

$title = '菜单';
$description = '编辑';
$breadcrumb[] = ['text' => $title, 'url' => '/admin/menu'];
$breadcrumb[] = ['text' => '编辑'];

$menus = AdminMenu::buildSelectOptions($data['_menu']);

$roles = AdminRole::getAll(['id', 'name']);
$roles = array_column($roles, 'name', 'id');

$permissions = AdminPermission::getAll(['id', 'name']);
$permissions = array_column($permissions, 'name', 'id');
$model = $data['model'] ?? [];
$menuRole = [];

if (!empty($model['roles'])) {
    $menuRole = array_column($model['roles'], 'name', 'id');
}
?>

@if(!empty($model))
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))
@endif

<div class="card card-success">
    <div class="card-header">
        <h3 class="card-title">{{ empty($model) ? trans('admin.create') : trans('admin.edit') }}</h3>
    </div>
    <form role="form" class="form-horizontal" action="/admin/menu/create" method="post">
        <div class="card-body">
            <div class="form-group row">
                <label class="asterisk control-label col-sm-2 col-form-label">父级菜单</label>
                <div class="input-group col-sm-10">
                    <select class="form-control select2" name="parent_id" required>
                        <option value ="0" {{ empty($model['parent_id']) ? 'selected="selected"' : "" }}>ROOT</option>
                        @foreach($menus as $menu)
                        <option value ="{{ $menu['id'] }}" {{ !empty($model['parent_id']) && $model['parent_id'] == $menu['id'] ? 'selected="selected"' : "" }}>{!! $menu['title'] !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="title" class="asterisk control-label col-sm-2 col-form-label">标题</label>
                <div class="input-group col-sm-10">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-pencil-alt"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="title" placeholder="{{ trans('admin.input') }} 标题" value="{{ $model['title'] ?? '' }}" required>
                </div>
            </div>
            <input type="hidden" name="id" value="{{ $model['id'] ?? 0 }}">
            <div class="form-group row">
                <label for="icon" class="asterisk control-label col-sm-2 col-form-label">图标</label>
                <div class="input-group col-sm-10">
                    <div class="input-group-prepend iconpicker-container">
                        <span class="input-group-text">
                            <i class="fa fa-bars"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="icon" value="fa-bars" placeholder="{{ trans('admin.input') }} 图标" value="{{ $model['icon'] ?? '' }}" required>
                </div>
                <label class="col-sm-2 col-form-label"></label>
                <span class="help-block col-sm-10 col-form-label">
                    <i class="fa fa-info-circle"></i>&nbsp;For more icons please see <a href="https://fontawesome.com/" target="_blank">https://fontawesome.com/</a>
                </span>
            </div>
            <div class="form-group row">
                <label for="order" class="control-label col-sm-2 col-form-label">排序</label>
                <div class="input-group col-sm-10">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-pencil-alt"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" name="order" placeholder="{{ trans('admin.input') }} 排序" value="{{ $model['order'] ?? '' }}">
                </div>
                <label class="col-sm-2 col-form-label"></label>
                <span class="help-block col-sm-10 col-form-label">
                    <i class="fa fa-info-circle"></i>&nbsp;数值越小越靠前, 最小为0
                </span>
            </div>
            <div class="form-group row">
                <label for="uri" class="control-label col-sm-2 col-form-label">路径</label>
                <div class="input-group col-sm-10">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-pencil-alt"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" id="uri" name="uri" placeholder="{{ trans('admin.input') }} 路径" value="{{ $model['uri'] ?? '' }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-2 col-form-label">角色</label>
                <div class="input-group col-sm-10">
                    <select class="asterisk form-control select2" multiple="multiple" data-placeholder="{{ trans('admin.select') }} 角色" name="roles[]">
                        @foreach($roles as $role_id => $role_name)
                        <option value ="{{ $role_id }}" {{ isset($menuRole[$role_id]) ? 'selected="selected"' : "" }}>{{ $role_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="control-label col-sm-2 col-form-label">权限</label>
                <div class="input-group col-sm-10">
                    <select class="form-control select2" name="permission">
                        @foreach($permissions as $permission_id => $permission_name)
                        <option value ="{{ $permission_id }}" {{ !empty($model['permission']) && $model['permission'] == $permission_id ? 'selected="selected"' : "" }}>{{ $permission_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success float-right">{{ empty($model) ? trans('admin.new') : trans('admin.save') }}</button>
            </div>
        </div>
    </form>
</div>
