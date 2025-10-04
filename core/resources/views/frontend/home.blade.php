@extends('frontend.layout.master')
@section('frontend_content')
    @php
        $breadcrumb = content('breadcrumb.content');
    @endphp

    @if ($page->is_breadcrumb == 1)
        <section class="breadcrumbs"
            style="background-image: url({{ getFile('breadcrumb', @$breadcrumb->data->backgroundimage) }});">
            <div class="container">
                <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center text-capitalize">
                    <h2>{{ __($pageTitle ?? '') }}</h2>
                    <ol>
                        <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                        <li>{{ __($pageTitle ?? '') }}</li>
                    </ol>
                </div>
            </div>
        </section>
    @endif


 <div class="pagebuilder-content">
        {!! convertHtml($page->html) !!}
    </div>
@endsection

 @push('style')
<style>
    {!! replaceBaseUrl($page->css) !!}
</style>
@endpush 


