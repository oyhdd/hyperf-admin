<?php
    $title = '消息列表';
    $description = 'show';
    $breadcrumb[] = ['text' => $title, 'url' => '/admin/message'];
    $breadcrumb[] = ['text' => '详情'];

    $directParams = @json_decode($model->direct_params, true);
    $imgs = $directParams['img'] ?? [];
    $directInfo = $directParams['direct_info'] ?? [];
?>
<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))


<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">{{ trans('admin.show') }}</h3>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">id</label>
                    <div class="input-group col-sm-7">
                        <input type="text" class="form-control bg-white" value="{{ $model->id }}" disabled="disabled">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">消息主题</label>
                    <div class="input-group col-sm-7">
                        <input type="text" class="form-control bg-white" disabled="disabled" value="{{ $model->topic }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">备注</label>
                    <div class="input-group col-sm-7">
                        <textarea type="text" class="form-control bg-white" disabled="disabled" rows="3">{{ $model->remark }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">推送类型</label>
                    <div class="input-group col-sm-7">
                        <input type="text" class="form-control bg-white" disabled="disabled" value="{{ $model::$push_type_label[$model->push_type] ?? '' }}" disabled="disabled">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">跳转类型</label>
                    <div class="input-group col-sm-7">
                        <input type="text" class="form-control bg-white" disabled="disabled" value="{{ $model->directType->name }}" disabled="disabled">
                    </div>
                </div>
                @if ($model->push_type == $model::PUSH_TYPE_ACTIVITY)
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">目标推送时间</label>
                    <div class="input-group col-sm-7">
                        <input type="text" class="form-control bg-white" disabled="disabled" value="{{ $model->push_time ?? '' }}" disabled="disabled">
                    </div>
                </div>
                @if (!empty($imgs))
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">图片</label>
                    <div class="input-group col-sm-7">
                        @foreach($imgs as $img)
                        @php
                            $img_url = str_replace('{lang}', 'en', $img['url']);
                        @endphp
                        <img src="{{ $img_url }}" class="mr-2 mt-2" style="width: 30%;">
                        <span>{{ $img['url'] }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">跳转链接</label>
                    <div class="input-group col-sm-7">
                        <textarea type="text" class="form-control bg-white" disabled="disabled" rows="2">{{ $directInfo['path'] ?? '' }}</textarea>
                    </div>
                </div>
                @endif
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">操作人</label>
                    <div class="input-group col-sm-7">
                        <input type="text" class="form-control bg-white" disabled="disabled" value="{{ $model->user->username }}" disabled="disabled">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">创建时间</label>
                    <div class="input-group col-sm-7">
                        <input type="text" class="form-control bg-white" disabled="disabled" value="{{ $model->create_time }}" disabled="disabled">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="text-right col-sm-3 col-form-label">更新时间</label>
                    <div class="input-group col-sm-7">
                        <input type="text" class="form-control bg-white" disabled="disabled" value="{{ $model->update_time }}" disabled="disabled">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>