<div class="form-group">
    <label class="control-label col-sm-{{ $width['label'] }} {{ !empty($required) ? 'asterisk' : '' }}">{{ $label }}</label>
    <div class="col-sm-{{ $width['field'] }}">
        <textarea class="form-control form_field_{{ $name }}" {!! $attributes !!}>{{ $value }}</textarea>
        @if ($help)
            <span class="help-block">
                <i class="fa fa-info-circle"></i>&nbsp;{!! $help !!}
            </span>
        @endif
    </div>
</div>