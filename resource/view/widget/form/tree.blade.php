<div class="form-group">
    <label class="control-label col-sm-{{ $width['label'] }} {{ !empty($required) ? 'asterisk' : '' }}">{{ $label }}</label>
    <div class="col-sm-{{ $width['field'] }}">
        <div class="input-group">
            <input id="{{ $id }}" type="hidden" name="{{ $name }}" {!! $attributes !!}/>
            <div class="jstree-wrapper">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="1">{{ trans('admin.select_all') }}
                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                        <input type="checkbox" value="2" {{ $expand ? 'checked' : ''}}>{{ trans('admin.expand') }}
                    </label>
                </div>
                <div class="da-tree" style="margin-top:10px"></div>
            </div>
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
        let $tree = $('.jstree-wrapper .da-tree'),
            $input = $("input[name='{{ $name }}']"),
            // $form = $("#{{ $id }}").closest('form'),
            opts = {!! $options !!},
            parents = [];

        opts.core.data = {!! $nodes !!};

        for (let i in opts.core.data) {
            if (opts.core.data[i]['parent'] != '#') {
                parents.push(opts.core.data[i]['parent'])
            }
        }
        parents = parents.filter((item, index, parents)=>{
            return parents.indexOf(item, 0) === index;
        });
        $('input[value=1]').on("click", function () {
            $(this).parents('.jstree-wrapper').find('.da-tree').jstree($(this).prop("checked") ? "check_all" : "uncheck_all");
        });
        $('input[value=2]').on("click", function () {
            $(this).parents('.jstree-wrapper').find('.da-tree').jstree($(this).prop("checked") ? "open_all" : "close_all");
        });

        $tree.on("changed.jstree", function (e, data) {
            var i, selected = [];

            $input.val('');
            for (i in data.selected) {
                if (parents.includes(Number(data.selected[i]))) { // ignore parent node
                    continue;
                }
                selected.push(data.selected[i]);
            }

            selected.length && $input.val(selected.join(','));
        }).on("ready.jstree", function (e, data) {
            @if($expand) 
                $(this).jstree(true).open_all();;
            @else
                $(this).jstree(true).close_all();
            @endif
        }).jstree(opts);
    })
</script>