<div class="form-group">
    <label class="control-label col-sm-{{ $width['label'] }} {{ !empty($required) ? 'asterisk' : '' }}">{{ $label }}</label>
    <div class="col-sm-{{ $width['field'] }}">
        <select class="form-control select2-hidden-accessible form_field_{{ $name }}" name="{{ $name }}[]" {!! $attributes !!}>
            <option></option>
            @foreach ($options as $k => $v) {
                <option value="{{ $k }}" {{ in_array($k, (array)$value) ? 'selected' : '' }}>{{ $v }}</option>
            }
            @endforeach
        </select>
        @if ($help)
            <span class="help-block">
                <i class="fa fa-info-circle"></i>&nbsp;{!! $help !!}
            </span>
        @endif
    </div>
</div>
<script>
    $("select.form_field_{{ $name }}").select2({"allowClear":true});
</script>