<div class="box-body form-horizontal">
    @foreach($show->getFields() as $column => $field)
    <div class="form-group">
        {!! $field['label'] !!}
        <div class="col-sm-{{ $show->getWidth('field') }}">
            {!! $field['input'] !!}
        </div>
    </div>
    @endforeach
</div>
<!-- .box-body -->