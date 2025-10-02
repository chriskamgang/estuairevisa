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
            <div class="col-md-6 col-xl-5">
                <div class="card">
                    <div class="card-body py-4">
                        <div class="text-center">
                            <h3 class="mb-1">{{ __(@$content->data->login_title) }}</h3>
                            <p class="mb-5">{{ __('Don\'t have an account') }}? <a href="{{route('user.register')}}" class="text-primary fw-semibold">{{__("Registration")}}</a></p>
                        </div>
                        <form action="{{ url()->current() }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="email"
                                    placeholder="{{ __('Enter Your Email') }}" autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="{{ __('Enter Password') }}" autocomplete="off">
                            </div>
                            <p class="text-end">
                                <a href="{{ route('user.forgot.password') }}" class="color-change">
                                    {{ __('Forget password?') }}
                                </a>
                            </p>
                            @if ($general->allow_recaptcha == 1)
                            <div class="col-md-12 my-3">
                                <script src="https://www.google.com/recaptcha/api.js"></script>
                                <div class="g-recaptcha" data-sitekey="{{ $general->recaptcha_key }}" data-callback="verifyCaptcha">
                                </div>
                                <div id="g-recaptcha-error"></div>
                            </div>
                            @endif
                            <button class="btn btn-primary w-100" type="submit"> {{ __('Log In') }} </button>
                        </form>

                        <div class="divider-or my-4 text-center">
                            <span>{{ __('or') }}</span>
                        </div>

                        <div class="d-flex flex-wrap gap-3">
                            @if ($general->google_status)
                            <a href="{{ route('user.google') }}" class="btn btn-secondary btn-md flex-grow-1">
                                <i class="fab fa-google"></i>
                                <span class="ms-1">{{ __('Google') }}</span>
                            </a>
                            @endif
                            @if ($general->facebook_status)
                            <a href="{{ route('user.facebook') }}" class="btn btn-secondary btn-md flex-grow-1">
                                <i class="fab fa-facebook-f"></i>
                                <span class="ms-1">{{ __('Facebook') }}</span>
                            </a>
                            @endif
                        </div>
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
                        "<span class='text-danger'>@changeLang('Captcha field is required.')}}</span>";
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