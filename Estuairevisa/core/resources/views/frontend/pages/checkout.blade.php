@extends('frontend.layout.master')
@section('content')

<div class=" checkout-slide">
    <div class="application-slide-inner">
        <div class="visa-progress-area">
            @foreach (['Documents', 'Details', 'Review', 'Checkout'] as $step)
            <div id="{{ strtolower($step) }}-progress"
                class="single-progress {{ $step === 'Checkout' ? 'active' : '' }}">
                <span class="circle"><i class="bi bi-check2"></i></span>
                <p class="mb-0">{{ __($step) }}</p>
            </div>
            @endforeach
        </div>

        <div class="container mt-5">
            <div id="checkout-wrapper">
                <div class="row gy-4 justify-content-center">
                    <div class="col-lg-7">
                        <h6 class="mb-4">{{ __("ORDER SUMMARY") }}</h6>

                        @php
                        $items = $checkout_data['items'] ?? [];
                        $subtotal = collect($items)->sum(fn($item) => $item['plan']->price);
                        @endphp

                        <div class="table-responsive">
                            <table class="table responsive-table">
                                <thead>
                                    <tr>
                                        <th>{{ __("Visa Type") }}</th>
                                        <th>{{ __("QTY") }}</th>
                                        <th>{{ __("Price") }}</th>
                                        <th>{{ __("Remove") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $trx => $data)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $data['plan']->title }}</div>
                                            <div class="text-muted small">
                                                {{ ucwords(str_replace('_', ' ', $data['plan']->plan_type)) }}
                                            </div>
                                        </td>
                                        <td>1</td>
                                        <td>{{ number_format($data['plan']->price, 2) }} USD</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger" type="submit">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            {{ __("No items in your order.") }}
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-primary fw-semibold">{{ __("Subtotal") }}</td>
                                        <td colspan="3" class="text-end text-primary fw-semibold">
                                            {{ number_format($subtotal, 2) }} USD
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div
                            class="d-flex flex-wrap align-items-center justify-content-between text-lg text-primary fw-semibold mt-4">
                            <span>Total</span>
                            <span id="total-price">{{ number_format($subtotal, 2) }} USD</span>
                        </div>


                    </div>
                    <div class="col-lg-5">

                        <button>{{__("Payment with balance")}}</button>
                        <h6 class="mb-4">{{ __("Payment Gateways") }}</h6>
                        <form id="paymentForm" action="{{ route('user.paynow', $gateways[0] ? $gateways[0]->id : 0) }}"
                            method="POST" class="submit_form">
                            @csrfs
                            <input type="hidden" name="amount" value="{{$subtotal}}">
                            <div class="row g-3">
                                @php $gatewayIndex = 0; @endphp
                                @foreach ($gateways ?? [] as $gateway)
                                <div class="col-md-12">
                                    <label class="w-100 gateway-option">
                                        <input type="radio" name="selected_gateway" value="{{ $gateway->id }}"
                                            class="gateway-radio" data-action="{{ route('user.paynow', $gateway->id) }}" {{
                                            $gatewayIndex===0 ? 'checked' : '' }}>
                                        <div class="gateway-card">
                                            <img src="{{ getFile('gateways', $gateway->gateway_image) }}" alt="{{ $gateway->name }}">
                                            <div class="gateway-content">
                                                <h4 class="title">
                                                    {{ $gateway->gateway_name === 'bank' ?
                                                    $gateway->gateway_parameters->name : $gateway->gateway_name }}
                                                </h4>
                                                <h4 class="subtitle">{{ __("Amount") }} {{ $subtotal }} USD</h4>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @php $gatewayIndex++; @endphp
                                @endforeach
                            </div>

                            <div class="d-flex align-items-center justify-content-center gap-3 mt-5">
                                <a href="{{route('home')}}" id="prev-review-btn " class="btn btn-md btn-secondary flex-grow-1">
                                    <i class="bi bi-chevron-left"></i> {{ __("Add New") }}
                                </a>

                                @auth
                                <button class="btn btn-primary flex-grow-1" type="submit">{{ __("Place Order") }}</button>
                                @else
                                <button type="button" class="btn btn-md btn-primary flex-grow-1" data-bs-toggle="modal"
                                    data-bs-target="#authModal">
                                    {{ __("Login / Register") }}
                                </button>
                                @endauth
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@guest
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <ul class="nav nav-tabs w-100 justify-content-center border-0" id="authTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab"
                            data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane"
                            aria-selected="true">
                            {{ __("Login") }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab"
                            data-bs-target="#register-tab-pane" type="button" role="tab"
                            aria-controls="register-tab-pane" aria-selected="false">
                            {{ __("Register") }}
                        </button>
                    </li>
                </ul>
                <button type="button" class="btn-close position-absolute end-0 me-3 mt-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body tab-content px-4 pb-4 pt-2" id="authTabContent">
        
                <div class="tab-pane fade show active" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab">
                    <form action="{{ route('user.login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="login-email" class="form-label">{{ __("Email") }}</label>
                            <input type="email" class="form-control" name="email" id="login-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label">{{ __("Password") }}</label>
                            <input type="password" class="form-control" name="password" id="login-password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{ __("Login") }}</button>
                        </div>
                    </form>
                </div>

  
                <div class="tab-pane fade" id="register-tab-pane" role="tabpanel" aria-labelledby="register-tab">
                    <form action="{{ route('user.register') }}" method="POST">
                        @csrf

                        <input type="hidden" name="place_order" value="1">
                        <div class="col-md-6">
                            <label>{{ __('First Name') }}</label>
                            <input type="text" class="form-control" name="fname" value="{{ old('fname') }}"
                                id="first_name" placeholder="{{ __('First Name') }}">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Last Name') }}</label>
                            <input type="text" class="form-control" name="lname" value="{{ old('lname') }}"
                                id="last_name" placeholder="{{ __('Last name') }}">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Username') }}</label>
                            <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                                id="username" placeholder="{{ __('User Name') }}">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Phone') }}</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" id="phone"
                                placeholder="{{ __('Phone') }}">
                        </div>
                        <div class="col-md-12">
                            <label>{{ __('Email') }}</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="email"
                                placeholder="{{ __('Email') }}">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Password') }}</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="{{ __('Password') }}">
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                id="password_confirmation" placeholder="{{ __('Confirm Password') }}">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">{{ __("Register") }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest

@endsection

@endpush

