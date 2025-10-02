@extends('backend.layout.master')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __($pageTitle) }}</h1>
            </div>


            <div class="card">
                <div class="card-body">
                    <form action="" method="post">

                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>{{ __('Google Client Id') }}</label>
                                <input type="text" name="google_client_id" class="form-control" placeholder="----------"
                                    value="{{$general->google_client_id}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Google Client Secret') }}</label>
                                <input type="text" name="google_client_secret" class="form-control"
                                    placeholder="----------" value="{{$general->google_client_secret}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Google Callback') }}</label>
                                <input type="text" readonly class="form-control"
                                name="google_callback"
                                    placeholder="----------" value="{{route('user.google.callback')}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Google Status') }}</label>
                                <select name="google_status" class="form-control" required>
                                    <option value="1" {{$general->google_status == 1 ? 'selected' : ''}} >{{ __("Active") }}</option>
                                    <option value="0" {{$general->google_status == 0 ? 'selected' : ''}} >{{ __("Inactive") }}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('Facebook Client Id') }}</label>
                                <input type="text" name="facebook_client_id" class="form-control"
                                    placeholder="----------" value="{{$general->facebook_client_id}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Facebook Client Secret') }}</label>
                                <input type="text" name="facebook_client_secret" class="form-control"
                                    placeholder="----------" value="{{$general->facebook_client_secret}}">
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('Facebook Callback') }}</label>
                                <input type="text" readonly class="form-control"
                                    name="facebook_callback"
                                    placeholder="----------" value="{{route('user.facebook.callback')}}">
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('Facebook Status') }}</label>
                                <select name="facebook_status" class="form-control" required >
                                    <option value="1" {{$general->facebook_status == 1 ? 'selected' : ''}} >{{ __("Active") }}</option>
                                    <option value="0" {{$general->facebook_status == 0 ? 'selected' : ''}} >{{ __("Inactive") }}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <button type="submit"
                                    class="btn btn-primary float-right">{{ __('Update Setting') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
