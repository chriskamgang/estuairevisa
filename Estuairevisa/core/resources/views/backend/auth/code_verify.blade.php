@extends('backend.auth.master')
@section('content')

    <section class="login-page">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="admin-login-wrapper">
                        <h3 class="text-dark text-center mb-4">{{__('Verify Using Code')}}</h3>
                        <form action="{{ route('admin.password.verify.code') }}" method="POST"
                            class="cmn-form mt-30">
                            @csrf
                            <div class="form-group">
                                <label class="text-white">{{__('Verification Code')}}</label>
                                <input type="text" name="code" id="code" class="form-control">
                            </div>
                            <div class="form-group text-right">
                                <a href="{{ route('admin.password.reset') }}"
                                    class="text--small">{{__('Try to send again')}}</a>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="login-button text-white w-100" tabindex="4">
                                    {{__('Verify Code')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('#code').on('input change', function() {
                var xx = document.getElementById('code').value;
                $(this).val(function(index, value) {
                    value = value.substr(0, 7);
                    return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
                });
            });
        })(jQuery)
    </script>
@endpush
