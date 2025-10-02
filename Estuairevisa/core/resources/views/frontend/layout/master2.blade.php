@extends('frontend.layout.app')
@section('content')


@php
    $breadcrumb = content('breadcrumb.content');
@endphp

@include('frontend.layout.header')

<section class="breadcrumbs"
    style="background-image: url({{ getFile('breadcrumb', @$breadcrumb->data->backgroundimage) }});">
    <div class="container">
        <div class="d-flex flex-wrap gap-3s justify-content-between align-items-center text-capitalize">
            <h2 class="mb-0">{{ __($pageTitle ?? '') }}</h2>
            <ol>
                <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li>{{ __($pageTitle ?? '') }}</li>
            </ol>
        </div>
    </div>
</section>


<div class="nir-user-dashboard">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-3">
                <div class="nir-user-sidebar h-100">
                    <div class="d-flex justify-content-center">
                        <img src="{{getFile('user',auth()->user()->image)}}" alt="image"
                            class="avatar-6xl rounded-circle object-fit-cover">
                    </div>
                    <div class="text-center mt-3">
                        <h5 class="mb-0">{{auth()->user()->fullname}}</h5>
                        <p class="mb-0">{{auth()->user()->username}}</p>
                    </div>
    
                    <hr>
    
                    @include('frontend.layout.user_sidebar')
                </div>
            </div>
            <div class="col-lg-9 ps-xxl-4">
                @yield('content2')
            </div>
        </div>
    </div>
</div>

@include('frontend.layout.footer')
@endsection