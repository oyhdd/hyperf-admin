<div class="form-group">
    <label class="control-label col-sm-{{ $width['label'] }}" style="padding-top: 3px;">{{ $label }}</label>
    <div class="col-sm-{{ $width['field'] }}">
        <div class="input-group">
            <input name="{{ $name }}" type="hidden" value="0" />
            <input class="form_field_{{ $name }}" {!! $attributes !!} @if(!empty($value)) checked @endif/>
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
        Switchery($('.form_field_{{ $name }}')[0], {
            size: 'small',
            color: '#00a65a'
        });
    })
</script>
