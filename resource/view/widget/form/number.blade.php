<div class="form-group">
    <label class="control-label col-sm-{{ $width['label'] }}">{{ $label }}</label>
    <div class="col-sm-{{ $width['field'] }}">
        <div class="input-group">
            <input class="form-control form_field_{{ $name }}" {!! $attributes !!}/>
        </div>
        @if ($help)
            <span class="help-block">
                <i class="fa fa-info-circle"></i>&nbsp;{!! $help !!}
            </span>
        @endif
    </div>
</div>

<script>
    $(function () {
        $('.form_field_{{ $name }}:not(.initialized)')
            .addClass('initialized')
            .bootstrapNumber({
                upClass: 'success',
                downClass: 'primary',
                center: true
            });
    })
</script>