@extends('frontend.layout.master2')
@section('content2')
        <div class="d-flex flex-wrap align-items-center gap-3 justify-content-between mb-4">
            <h5 class="mb-0">{{ __('Profile Settings') }}</h5>
            <a href="{{ route('user.change.password') }}" class="btn btn-primary btn-sm">{{ __('Change Password') }}</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('user.profileupdate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-xxl-4 col-xl-5 col-lg-6 justify-content-center">
                            <div class="img-choose-div m-auto h-75">
                                <label class="mb-1 form-label">{{ __('Profile Picture') }}</label>


                                <img class=" rounded file-id-preview" id="file-id-preview"
                                    src="{{ getFile('user', Auth::user()->image) }}" alt="pp">

                                <input type="file" name="image" id="imageUpload" class="upload"
                                    accept=".png, .jpg, .jpeg" hidden>

                                <label for="imageUpload" class="editImg btn btn-primary btn-md w-100 mt-3">{{ __('Choose file') }}</label>


                            </div>
                        </div>
                        <div class="col-xxl-8 col-xl-7 col-lg-6">
                            <div class="row gy-3">
                                <div class="col-12">
                                    <label class="form-label">{{ __('First Name') }}</label>
                                    <input type="text" class="form-control" name="fname"
                                        value="{{ Auth::user()->fname }}" placeholder="{{ __('Enter First Name') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('Last Name') }}</label>
                                    <input type="text" class="form-control" name="lname"
                                        value="{{ Auth::user()->lname }}" placeholder="{{ __('Enter Last Name') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('Username') }}</label>
                                    <input type="text" class="form-control text-white" name="username"
                                        value="{{ Auth::user()->username }}" placeholder="{{ __('Enter User Name') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('Email address') }}</label>
                                    <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}"
                                        placeholder="{{ __('Enter Email') }}">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">{{ __('Phone') }}</label>
                                    <input type="text" class="form-control" name="phone" value="{{ Auth::user()->phone }}"
                                        placeholder="{{ __('Enter Phone') }}">
                                </div>



                            
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('Country') }}</label>
                                    <input type="text" name="country" class="form-control"
                                        value="{{ Auth::user()->address->country ?? '' }}">
                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">{{ __('city') }}</label>
                                    <input type="text" name="city" class="form-control form_control"
                                        value="{{ Auth::user()->address->city ?? '' }}">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">{{ __('zip') }}</label>
                                    <input type="text" name="zip" class="form-control form_control"
                                        value="{{ Auth::user()->address->zip ?? '' }}">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">{{ __('state') }}</label>
                                    <input type="text" name="state" class="form-control form_control"
                                        value="{{ Auth::user()->address->state ?? '' }}">

                                </div>

                            </div>

                            <button class="btn btn-primary mt-4">{{ __('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection


@push('script')
    <script>
        $(function() {
            'use strict'
            document.getElementById("imageUpload").onchange = function() {
                show();
            };

            function show() {
                if (event.target.files.length > 0) {
                    var src = URL.createObjectURL(event.target.files[0]);
                    var preview = document.getElementById("file-id-preview");
                    preview.src = src;
                    preview.style.display = "block";
                    document.getElementById("file-id-preview").style.visibility = "visible";
                }
            }
        });
    </script>
@endpush
