<!-- footer -->
<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> {{ config("admin.version") ?? "1.0.0" }}
    </div>
    <strong>Powered by <a href="https://github.com/oyhdd/hyperf-admin" target="_blank">hyperf-admin</a></strong>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <!-- memory usage -->
    @if(config('admin.footer.show_memory_usage'))
        @php
            $_memory_usage = memory_usage();
            if ($_memory_usage <= 80) {
                $_memory_usage_label = 'text-success';
            } elseif ($_memory_usage <= 120) {
                $_memory_usage_label = 'text-warning';
            } else {
                $_memory_usage_label = 'text-danger';
            }
        @endphp
        &nbsp;&nbsp;memory usage: <span class="{{ $_memory_usage_label }}">{{ $_memory_usage }}M</span>
    @endif

    <!-- page load time -->
    @if(config('admin.footer.show_load_time'))
        &nbsp;&nbsp;load: <span id="footer_show_load_time"></span>
    @endif
</footer>

<script type="text/javascript">
    $(function(){
        var loadTime = window.performance.timing.domContentLoadedEventEnd - window.performance.timing.navigationStart
        $("#footer_show_load_time").removeClass()
        if (loadTime < 1000) {
            $("#footer_show_load_time").addClass('text-success')
        } else if (loadTime < 2000) {
            $("#footer_show_load_time").addClass('text-warning')
        } else {
            $("#footer_show_load_time").addClass('text-danger')
        }
        $("#footer_show_load_time").text(loadTime + "ms")
    })
</script>