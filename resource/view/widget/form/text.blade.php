<div class="form-group">
    <label class="control-label col-sm-{{ $width['label'] }} {{ !empty($required) ? 'asterisk' : '' }}">{{ $label }}</label>
    <div class="col-sm-{{ $width['field'] }}">
        <div class="input-group">
            @if ($prepend)
                <div class="input-group-addon">
                    {!! $prepend !!}
                </div>
            @endif

            <input class="form-control form_field_{{ $name }}" {!! $attributes !!}/>

        </div>
        @if ($help)
            <span class="help-block">
                <i class="fa fa-info-circle"></i>&nbsp;{!! $help !!}
            </span>
        @endif
    </div>
</div>