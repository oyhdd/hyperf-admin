@if($toastr = get_toastr())
    @php
        $type    = \Hyperf\Utils\Arr::get($toastr->get('type'), 0, 'success'); // success, info, error, warning
        $message = \Hyperf\Utils\Arr::get($toastr->get('message'), 0, '');
        $timeout = (intval($_data['site']['toastr_timeout'] ?? 4)) * 1000;
    @endphp

    <script type="text/javascript">
      $(function() {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: 'slideDown',
                timeOut: "{!! $timeout !!}",
            };
            toastr["{!!  $type !!}"]("{!! $message !!}");
        })
    </script>
@endif