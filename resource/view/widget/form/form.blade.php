<form id="form_{{ $form->getElementId() }}" method="{{ $form->method() }}" accept-charset="UTF-8" {{ $form->getEnctype() }} pjax-container
    action="{{ $form->action() ?: $_data['path'] }}">
    <div class="box-body form-horizontal">
        @foreach($form->getFields() as $field)
            {!! $field->render() !!}
        @endforeach
    </div>
    <!-- .box-body -->

    {!! $form->getFooter() !!}
    <!-- /.box-footer -->
</form>
<script type="text/javascript">
    $(function() {
        let form = $("#form_{{ $form->getElementId() }}");
        let submitBtn = form.find(':submit');
        form.submit(function (e) {
            e.preventDefault();
            submitBtn.button('loading')

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(ret) {
                    submitBtn.button('reset');
                    if (ret.data && ret.data.redirect) {
                        $.pjax({
                            url: ret.data.redirect,
                            container: '#pjax-container'
                        });
                    }
                },
                error:function(xhq){
                    submitBtn.button('reset');
                    var errorData = JSON.parse(xhq.responseText);
                    if (errorData) {
                        toastr.error(errorData.message);
                    }
                }
            });

            return false;
        });


    })
</script>