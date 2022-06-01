@if (isset($title))
    <title>{{ config('admin.title') }} | {{ $title }}</title>

    <section class="content-header">
        <h1>
            {{ $title }}
            <small>{{ $description ?? '' }}</small>
        </h1>
        @if (isset($breadcrumb))
        <ol class="breadcrumb" style="padding-right: 30px;">
            <li><a class="new-tab-link" href="{{ admin_url() }}" data-title="{{ trans('admin.home') }}"><i class="fa fa-dashboard"></i>{{ trans('admin.home') }}</a></li>
            @foreach($breadcrumb as $label)
            <li>
                {{ $label }}
            </li>
            @endforeach
        </ol>
        @endif
    </section>
@endif