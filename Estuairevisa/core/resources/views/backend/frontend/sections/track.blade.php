<div class="py-100 track-section">
    <div class="container">

        <h3 class="mb-4 text-center">{{ __("Track Visa") }}</h3>


        <form method="GET" action="{{ route('visa.track') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="order_number" class="form-control" placeholder="{{ __('Enter Order Number') }}"
                    value="{{ request('order_number') }}">
                <button class="btn btn-primary" type="submit">{{ __('Track') }}</button>
            </div>
        </form>

        <!-- Result Section -->
        @if(request('order_number'))
            @if($visa)
                <div class="main-content">
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ __("Visa Application Details") }}</h5>
                        </div>

                        <div class="card-body">
                            <!-- Visa Details -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6>{{ __("Order Number") }}</h6>
                                    <p>{{ $visa->order_number }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>{{ __("Plan") }}</h6>
                                    <p>{{ $visa->plan->title }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>{{ __("Price") }}</h6>
                                    <p>{{ number_format($visa->price, 2) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>{{ __("Status") }}</h6>
                                    <p>{!! $visa->status() !!}</p>
                                </div>
                            </div>

                            <hr>

                            <!-- Personal Info -->
                            <h5 class="mb-3">{{ __("Personal Info") }}</h5>
                            <div class="row mb-3">
                                @foreach($visa->personal_info as $name => $value)
                                <div class="col-md-6">
                                    <h6>{{ ucwords(str_replace('_', ' ', $name)) }}</h6>
                                    <p>{{ $value }}</p>
                                </div>
                                @endforeach
                            </div>



                            <hr>

                            <!-- Additional Info -->
                            <h5 class="mb-3">{{ __("Additional Info") }}</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>{{ __("Created At") }}:</strong> {{ $visa->created_at->format('Y-m-d') }}
                                </li>
                                <li class="list-group-item">
                                    <strong>{{ __("Updated At") }}:</strong> {{ $visa->updated_at->format('Y-m-d') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mt-3">
                    {{ __("No data found for Order Number") }} <strong>{{ request('order_number') }}</strong>.
                </div>
            @endif
        @else
            <div class="before-track-search text-center">
                <img src="assets/images/tracking.png" alt="image">
                <p class="text-center mb-0 mt-5">{{ __('Enter your order number to track the status of your visa
                    application.') }}</p>
            </div>
        @endif
    </div>
</div>