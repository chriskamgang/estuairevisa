@extends('frontend.layout.master2')
@section('content2')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('user.update.password') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Old Password') }}</label>
                    <input type="password" class="form-control" name="oldpassword"
                        placeholder="{{ __('Enter Old Password') }}">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">{{ __('New Password') }}</label>
                    <input type="password" class="form-control" name="password"
                        placeholder="{{ __('Enter New Password') }}">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">{{ __('Confirm Password') }}</label>
                    <input type="password" class="form-control" name="password_confirmation"
                        placeholder="{{ __('Confirm Password') }}">
                </div>

                <div class="row mt-4">
                    <div class="col-xs-12 d-grid gap-2">
                        <button class="btn  btn-primary" type="submit">{{ __('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
