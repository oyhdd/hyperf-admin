<?php

use App\Model\MessageDirectType;

$title = '消息列表';
$description = 'edit';
$breadcrumb[] = ['text' => $title, 'url' => '/admin/message'];
$breadcrumb[] = ['text' => '编辑'];

$directTypes = MessageDirectType::getAll(['id', 'name', 'remark']);
$directParams = @json_decode($model->direct_params, true);
$directInfo = $directParams['direct_info'] ?? [];
$imgs = $directParams['img'] ?? [];
$imgs = implode(",\r\n", array_column($imgs, 'url'));
?>

<!-- 引入面包屑 -->
@include('layout.breadcrumb', compact('title', 'description', 'breadcrumb'))

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ trans('admin.edit') }}</h3>
                </div>
                <form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="control-label col-sm-2 col-form-label">id</label>
                            <div class="input-group col-sm-8">
                                <input type="text" class="form-control" name="id" value="{{ $model->id }}" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="topic" class="asterisk control-label col-sm-2 col-form-label">消息主题</label>
                            <div class="input-group col-sm-8">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-pencil-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="topic" value="{{ $model->topic }}" placeholder="{{ trans('admin.input') }} 消息主题" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="remark" class="asterisk control-label col-sm-2 col-form-label">备注</label>
                            <div class="input-group col-sm-8">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-pencil-alt"></i>
                                    </span>
                                </div>

                                <textarea type="text" class="form-control" name="remark" id="remark" placeholder="{{ trans('admin.input') }} 备注" rows="3" required>{{ $model->remark }}</textarea>
                            </div>
                        </div>
                        @if ($model->push_type == $model::PUSH_TYPE_ACTIVITY)
                        <div class="form-group row">
                            <label for="push_time" class="asterisk col-sm-2 control-label col-form-label">目标推送时间</label>
                            <div class="input-group col-sm-8">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input type="text" class="form-control" name="push_time" id="push_time">
                            </div>
                        </div>
                        @endif
                        <div class="form-group row">
                            <label class="control-label col-sm-2 col-form-label">推送类型</label>
                            <div class="input-group col-sm-8">
                                <input type="text" class="form-control" value="{{ $model::$push_type_label[$model->push_type] ?? '' }}" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="asterisk control-label col-sm-2 col-form-label">跳转类型</label>
                            <div class="input-group col-sm-8">
                                <select class="form-control select2" name="direct_type" required>
                                @foreach($directTypes as $directType)
                                    <option value ="{{ $directType['id'] }}" {{ ($directType['id'] == $model->direct_type) ? "selected" : "" }} {{ ($model->push_type == $model::PUSH_TYPE_ACTIVITY && $directType['name'] != 'h5') ? 'disabled="disabled"' : '' }}>
                                        {{ $directType['name'] }}: {{ $directType['remark'] }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($model->push_type == $model::PUSH_TYPE_ACTIVITY)
                        <div class="form-group row">
                            <label for="img" class="control-label col-sm-2 col-form-label">图片</label>
                            <div class="input-group col-sm-8">
                                <textarea type="text" class="form-control" name="img"  id="img" placeholder="{{ trans('admin.input') }} 图片链接，多张时以逗号相隔" rows="3">{{ $imgs }}</textarea>
                            </div>
                            <label class="col-sm-2 control-label col-form-label"></label>
                            <label class="col-sm-2 control-label col-form-label"></label>
                            <span class="help-block col-sm-8 col-form-label">
                                <i class="fa fa-info-circle"></i>&nbsp;输入完整链接，其中语言使用{lang}替换 如：http://cdn-4.jjshouse.com/v5res/jjshouse/2020-05-12/images/banners/samplesale/samplesale/750/{lang}.jpg
                            </span>
                        </div>
                        <div class="form-group row">
                            <label for="direct_url" class="control-label col-sm-2 col-form-label">跳转链接</label>
                            <div class="input-group col-sm-8">
                                <textarea type="text" class="form-control" name="direct_url" id="direct_url" placeholder="{{ trans('admin.input') }} 跳转链接" rows="2">{{ $directInfo['path'] ?? '' }}</textarea>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="customFile" class="control-label col-sm-2 col-form-label">导入翻译</label>
                            <div class="input-group col-sm-8 custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="customFile">
                                <label class="custom-file-label" for="customFile">选择文件</label>
                            </div>
                            <label class="col-sm-2 control-label col-form-label"></label>
                            <label class="col-sm-2 control-label col-form-label"></label>
                            <span class="help-block col-sm-8 col-form-label">
                                <i class="fa fa-info-circle"></i>&nbsp;可点击 <a href="/message-language/translate.xlsx" target="_blank">下载模板</a>
                            </span>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-2 col-form-label">操作人</label>
                            <div class="input-group col-sm-8">
                                <input type="text" class="form-control" value="{{ $model->user->username ?? '' }}" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-2 col-form-label">创建时间</label>
                            <div class="input-group col-sm-8">
                                <input type="text" class="form-control" disabled="disabled" value="{{ $model->create_time }}" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-2 col-form-label">更新时间</label>
                            <div class="input-group col-sm-8">
                                <input type="text" class="form-control" disabled="disabled" value="{{ $model->update_time }}" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary float-right">{{ trans('admin.save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        bsCustomFileInput.init();

        var startDate = '{{ $model->push_time == "0000-00-00 00:00:00" ? '' : $model->push_time }}';
        if (startDate == '') {
            startDate = moment().format('YYYY-MM-DD HH:mm:ss');
        }
        $('#push_time').daterangepicker({
            timePicker: true,
            singleDatePicker: true,
            timePicker24Hour : true,
            startDate: startDate,
            minDate: startDate,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
            }
        })
    })
</script>