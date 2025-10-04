@extends('frontend.layout.auth')
@php

$content = content('breadcrumb.content');

@endphp

@section('content')
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
                        @if ($general->is_email_verification_on && !auth()->user()->ev)
                            <div class="text-center">
                                <h3 class="mb-5">{{ __('Verify Email') }}</h3>
                            </div>
                            <form class="reg-form" action="{{ route('user.authentication.verify.email') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label"> {{ __('Verification Code') }}</label>
                                    <input type="text" name="code" class="form-control"
                                        placeholder="{{ __('Enter Verification Code') }}">
                                </div>
                                @if ($general->allow_recaptcha)
                                    <div class="mb-3">
                                        <script src="https://www.google.com/recaptcha/api.js"></script>
                                        <div class="g-recaptcha" data-sitekey="{{ $general->recaptcha_key }}"
                                            data-callback="verifyCaptcha"></div>
                                        <div id="g-recaptcha-error"></div>
                                    </div>
                                @endif
                                <button class="btn btn-primary w-100" type="submit"> {{ __('Verify Now') }} </button>
                            </form>
                        @elseif($general->is_sms_verification_on && !auth()->user()->sv)
                            <form method="POST" action="{{ route('user.authentication.verify.sms') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">{{ __('Sms Verify Code') }}</label>
                                    <input type="text" name="code" class="form-control" id="exampleInputEmail1"
                                        aria-describedby="emailHelp">
                                </div>

                                <button type="submit" class="btn btn-primary w-100">{{ __('Verify Now') }}</button>

                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontend.layout.footer')
@endsection
