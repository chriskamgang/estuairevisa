@extends('frontend.layout.auth')
@section('content')
@php
    $content = content('auth_section.content');
@endphp
@push('seo')
    <meta name='description' content="{{ $general->seo_description }}">
@endpush

@include('frontend.layout.header')
@php
    $breadcrumb = content('breadcrumb.content');
@endphp

<section class="breadcrumbs"
    style="background-image: url({{ getFile('breadcrumb', @$breadcrumb->data->backgroundimage) }});">
    <div class="container">
        <div class="d-flex flex-wrap gap-3s justify-content-between align-items-center text-capitalize">
            <h2>{{ __($pageTitle ?? '') }}</h2>
            <ol>
                <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li>{{ __($pageTitle ?? '') }}</li>
            </ol>
        </div>
    </div>
</section>
 
<div class="py-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body py-4">
                        <div class="text-center">
                            <h3 class="mb-5">{{ __(@$content->data->forget_password_title) }}</h3>
                        </div>
                        <form action="{{ route('user.forgot.password') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="{{ __('Enter Email') }}">
                            </div>
                            @if ($general->allow_recaptcha == 1)
                                <div class="mb-3">
                                    <script src="https://www.google.com/recaptcha/api.js"></script>
                                    <div class="g-recaptcha" data-sitekey="{{ $general->recaptcha_key }}"
                                        data-callback="verifyCaptcha"></div>
                                    <div id="g-recaptcha-error"></div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <button type="submit" id="recaptcha"
                                    class="btn btn-primary w-100">{{ __('Send Verification Code') }}</button>
                            </div>
                            <div>
                                <p class="text-center">{{ __('Login Again') }}? <a href="{{ route('user.login') }}" class="text-primary fw-semibold">{{ __('Login') }}</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('frontend.layout.footer')
@endsection


@push('script')
    <script>
        $(function() {
            "use strict";

            function submitUserForm() {
                var response = grecaptcha.getResponse();
                if (response.length == 0) {
                    document.getElementById('g-recaptcha-error').innerHTML =
                        "<span class='text-danger'>{{__('Captcha field is required.')</span>";
                    return false;
                }
                return true;
            }

            function verifyCaptcha() {
                document.getElementById('g-recaptcha-error').innerHTML = '';
            }
        })
    </script>
@endpush
