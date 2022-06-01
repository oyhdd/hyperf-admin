<form id="form_{{ $form->getElementId() }}" class="form-horizontal" method="{{ $form->method() }}" accept-charset="UTF-8" pjax-container
    action="{{ $form->action() ?: $_data['path'] }}">
    <div class="box-body">
        @foreach($form->getFields() as $column => $field)
        <div class="form-group">
            {!! $field['label'] !!}
            <div class="col-sm-{{ $form->getWidth('field') }}">
                {!! $field['input'] !!}
            </div>
        </div>
        @endforeach
        @foreach($form->getHiddenFields() as $colmun => $field)
            {!! $field['input'] !!}
        @endforeach
    </div>
    <!-- .box-body -->

    {!! $form->getFooter() !!}
    <!-- /.box-footer -->
</form>