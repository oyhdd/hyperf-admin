<div class="input-group" style="width:100%">
    <input type="hidden" name="{{ $column }}">
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

<script>
    $(function () {
        let $tree = $('.jstree-wrapper .da-tree'),
            $input = $("input[name='{{ $column }}']"),
            opts = {
                "plugins": ["checkbox", "types"],
                "core": {
                    "check_callback": true,
                    "themes": {
                        "name": "proton",
                        "responsive": true,
                        "ellipsis": true
                    },
                    "dblclick_toggle": false
                },
                "checkbox": {
                    "keep_selected_style": false,
                    "three_state": true,
                    "cascade_to_disabled": false,
                    "whole_node": false
                },
                "types": {
                    "default": {"icon": false}
                }
            },
            parents = [];

        opts.core.data = {!! $data !!};

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
                console.log(data.selected[i])
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