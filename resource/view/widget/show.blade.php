<div class="box box-{{ $show->style() }}">
    <div class="box-header with-border">
        <h3 class="box-title">{{ $show->title() }}</h3>

        <div class="box-tools pull-right">
            {!! $show->renderTools() !!}
        </div>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        @include('widget.show.show')
    </div>
</div>
