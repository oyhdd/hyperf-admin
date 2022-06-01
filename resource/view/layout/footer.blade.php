@if (config('admin.show_footer'))
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> {{ config('admin.version', '1.0.0') }}
    </div>
    <strong>Powered by <a href="https://github.com/oyhdd/hyperf-admin">Hyperf Admin</a>.</strong>
</footer>
@endif