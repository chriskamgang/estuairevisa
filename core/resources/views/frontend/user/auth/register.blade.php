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

<section class="py-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body py-4">
                        <div class="text-center">
                            <h3 class="mb-1">{{ __(@$content->data->register_title) }}</h3>
                            <p class="mb-5">{{ __('Already registereds') }}? <a href="{{route('user.login')}}" class="text-primary fw-semibold">{{__("Login")}}</a></p>
                        </div>

                        <form action="{{ route('user.register') }}" method="POST">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-lg-12">
                                    @if (isset(request()->reffer))
                                    <label class="form-label">{{ __('Referred By') }}</label>
                                    <input type="text" class="form-control" value="{{ request()->reffer }}"
                                        name="reffered_by" placeholder="{{ __('Referred By') }}" readonly>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('First Name') }}</label>
                                    <input type="text" class="form-control" name="fname" value="{{ old('fname') }}"
                                        id="first_name" placeholder="{{ __('First Name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('Last Name') }}</label>
                                    <input type="text" class="form-control" name="lname" value="{{ old('lname') }}"
                                        id="last_name" placeholder="{{ __('Last name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('Username') }}</label>
                                    <input type="text" class="form-control" name="username"
                                        value="{{ old('username') }}" id="username"
                                        placeholder="{{ __('User Name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('Phone') }}</label>
                                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                        id="phone" placeholder="{{ __('Phone') }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">{{ __('Email') }}</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                        id="email" placeholder="{{ __('Email') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('Password') }}</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="{{ __('Password') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('Confirm Password') }}</label>
                                    <input type="password" class="form-control" name="password_confirmation"
                                        id="password_confirmation" placeholder="{{ __('Confirm Password') }}">
                                </div>
                                <div class="col-md-12">
                                    @if ($general->allow_recaptcha == 1)
                                    <script src="https://www.google.com/recaptcha/api.js"></script>
                                    <div class="g-recaptcha" data-sitekey="{{ $general->recaptcha_key }}"
                                        data-callback="verifyCaptcha"></div>
                                    <div id="g-recaptcha-error"></div>
                                    @endif
                                </div>
                                <div class="col-lg-12">
                                    <button class="btn btn-primary w-100" type="submit"> {{ __('Register') }} </button>
                                </div>
                            </div>
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
</section>

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
                        "<span class='text-danger'>{{ __('Captcha field is required.') }}</span>";
                    return false;
                }
                return true;
            }

            function verifyCaptcha() {
                document.getElementById('g-recaptcha-error').innerHTML = '';
            }
        });
</script>
@endpush