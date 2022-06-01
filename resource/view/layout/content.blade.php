@php extract($data); @endphp

<section class="content-header"></section>

<section class="content">
@if ($_data['ha_no_animation'])
    <div class="pjax-container-content">
@else
    <div class="pjax-container-content animated {{ $_data['site']['animation_type'] ?? '' }}" style="animation-duration: {{ $_data['site']['animation_duration'] ?? '0' }}s;-webkit-animation-duration: {{ $_data['site']['animation_duration'] ?? '0' }}s;animation-delay:{{ $_data['site']['animation_delay'] ?? '0' }}s;-webkit-animation-delay: {{ $_data['site']['animation_delay'] ?? '0' }}s;">
@endif
        @include($_data['view'])
    </div>
</section>


<script>
    $(function () {
        $(".content-wrapper>.content-header").html($(".pjax-container-content>.content-header").html())
        $(".pjax-container-content>.content-header").hide()
    })
</script>

@include('widget.toastr')