@php
    if (!empty($data['_user']) && !empty($data['_user']['customize_style'])) {
        $customize_style = $data['_user']['customize_style'];
    } else {
        $customize_style = '{}';
    }
@endphp

<script type="text/javascript">
    $(function () {
        let customize_style = {!! $customize_style !!}
        for (let selector in customize_style) {
            for (let class_name in customize_style[selector]) {
                var separator = '.'
                if (selector == 'body') {
                  separator = ''
                }
                if (customize_style[selector][class_name]) {
                    $("#"+selector+"_"+class_name).attr("checked", true)
                    $(separator+selector).addClass(class_name)
                } else {
                    $("#"+selector+"_"+class_name).attr("checked", false)
                    $(separator+selector).removeClass(class_name)
                }
            }
        }

        //Initialize Select2 Elements
        $('.select2').select2()

        $('#nav_sidebar_collapse').click(function () {
            if ($("body").hasClass("sidebar-collapse")) {
                saveCustomizeStyle('body', 'sidebar-collapse', 0)
            } else {
                saveCustomizeStyle('body', 'sidebar-collapse', 1)
            }
        })
    })

    function saveCustomizeStyle(selector, class_name, enable, reload) {
        var styles = {
            [class_name]: enable
        }
        $.ajax({
            url: '/admin/user/saveCustomizeStyle',
            type: 'POST',
            data: {
                selector: selector,
                styles: styles
            },
            success: function(retData) {
                if (reload) {
                    location.reload()
                }
            },
            error: function(retData) {}
        });
    }
</script>

<script src="/vendor/hyperf-admin/AdminLTE/dist/js/hyperf-admin.js"></script>