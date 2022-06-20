<div class="form-group">
    <label class="control-label col-sm-{{ $width['label'] }}">{{ $label }}</label>
    <div class="col-sm-{{ $width['field'] }}">
        <input class="form-control form_field_{{ $name }} bg-white" {!! $attributes !!}/>
        @if ($help)
            <span class="help-block">
                <i class="fa fa-info-circle"></i>&nbsp;{!! $help !!}
            </span>
        @endif
    </div>
</div>