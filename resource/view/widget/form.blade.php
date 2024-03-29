<style>
    .box-body > form > .box-footer {
        padding-bottom: 0 !important;
    }
</style>
<div class="box box-{{ $form->style() }}">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $form->title() }}</h3>

        <div class="box-tools pull-right">
            {!! $form->renderTools() !!}
        </div>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        @include('widget.form.form')
    </div>
</div>