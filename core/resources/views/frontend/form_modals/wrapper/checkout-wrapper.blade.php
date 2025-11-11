<div class="application-slide checkout-slide">
    <div class="application-slide-dialog">
        <div class="application-slide-content">
            <div class="application-slide-body">
                <div class="visa-progress-area">
                    @foreach (['Documents', 'Details', 'Review', 'Checkout'] as $step)
                    <div id="{{ strtolower($step) }}-progress"
                        class="single-progress {{ $step === 'Checkout' ? 'active' : '' }}">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{ __($step) }}</p>
                    </div>
                    @endforeach
                </div>
        
                <div class="mt-sm-5 mt-4">
                    <div id="checkout-wrapper">
                        <div class="row gy-4 justify-content-center">
                            <div class="col-lg-7">
                                <h6 class="mb-sm-4 mb-3">{{ __("ORDER SUMMARY") }}</h6>
        
                                @php
                                $items = $checkout_data['items'] ?? [];
                                $subtotal = collect($items)->sum(fn($item) => $item['plan']->price);
                                @endphp
        
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __("Visa Type") }}</th>
                                                <th>{{ __("Visa for") }}</th>
                                                <th>{{ __("Price") }}</th>
                                                <th>{{ __("Remove") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($items as $trx => $data)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold text-nowrap">{{ $data['plan']->title }}</div>
                                                    <div class="text-muted small">

                                                          {{ ucwords(str_replace('_', ' ', $data['plan']->plan_type)) }}

                                                    </div>
                                                </td>
                                                <td>{{ $data['destination_country']->name ?? 'N/A' }}</td>
                                                <td class="text-nowrap">{{ number_format($data['plan']->price, 2) }} {{$general->site_currency}}</td>
                                                <td>
                                                    <a href="{{route('visa.cart.remove',$trx)}}" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-x-lg"></i>
                                                    </a>
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
                                                <td class="text-primary fw-semibold">{{ __("Subtotal :") }}</td>
                                                <td colspan="3" class="text-end text-primary fw-semibold">
                                                    {{ number_format($subtotal, 2) }} {{$general->site_currency}}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
        
                                <div
                                    class="d-flex flex-wrap align-items-center justify-content-between text-lg text-primary fw-semibold mt-sm-4">
                                    <span>Total</span>
                                    <span id="total-price">{{ number_format($subtotal, 2) }} {{$general->site_currency}}</span>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                @auth
                                <button class="btn btn-primary w-100 mb-3 payment_with_balance_btn">{{__("Payment with
                                    balance")}}</button>
                                    @endauth
                                <h6 class="mb-4">{{ __("Payment Gateways") }}</h6>
                                <form id="paymentForm" action="{{ route('user.paynow', $gateways[0] ? $gateways[0]->id : 0) }}"
                                    method="POST" class="submit_form">
                                    @csrf
                                    <input type="hidden" name="amount" value="{{$subtotal}}">
                                    <div class="checkout-payment-gateways">
                                        <div class="row g-3">
                                            @php $gatewayIndex = 0; @endphp
                                            @foreach ($gateways ?? [] as $gateway)
                                            <div class="col-md-12">
                                                <label class="w-100 gateway-option">
                                                    <input type="radio" name="selected_gateway" value="{{ $gateway->id }}"
                                                        class="gateway-radio" data-action="{{ route('user.paynow', $gateway->id) }}"
                                                        {{ $gatewayIndex===0 ? 'checked' : '' }}>
                                                    <div class="gateway-card">
                                                        <img src="{{ getFile('gateways', $gateway->gateway_image) }}"
                                                            alt="{{ $gateway->name }}">
                                                        <div class="gateway-content">
                                                            <h4 class="title">
                                                                {{ $gateway->gateway_name === 'bank' ?
                                                                $gateway->gateway_parameters->name : $gateway->gateway_name }}
                                                            </h4>
                                                            <h4 class="subtitle">{{ __("Amount") }} {{ $subtotal }}
                                                                {{$general->site_currency}}</h4>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                            @php $gatewayIndex++; @endphp
                                            @endforeach
                                        </div>
                                    </div>
        
                                    <div class="d-flex align-items-center justify-content-center gap-3 mt-sm-5 mt-4">
                                        <a href="{{route('home')}}" id="prev-review-btn flex-grow-1" class="btn btn-md btn-secondary">
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
                        <div class="row gy-4">
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
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                                    id="username" placeholder="{{ __('User Name') }}">
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
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">{{ __("Register") }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest


@auth
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="{{route('user.visa.payment')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="header-title">{{ __("Payment with balance") }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>{{ __("Are you sure you want to proceed with this payment using your balance?") }}</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                    <button type="submit" class="btn btn-primary">{{ __("Confirm Payment") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth


<style>
    .gateway-option input[type="radio"] {
        display: none;
    }

    .gateway-card {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .gateway-option input[type="radio"]:checked+.gateway-card {
        border-color: #0d6efd;
        /* Bootstrap Primary */
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
        background-color: #f0f8ff;
    }
</style>



<script>
    'use strict';
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.extra-fee-checkbox');
        const totalDisplay = document.getElementById('total-price');
        const baseSubtotal = parseFloat(`{{ $subtotal }}`);

        function updateTotal() {
            let extraTotal = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    extraTotal += parseFloat(cb.dataset.amount);
                }
            });

            const total = (baseSubtotal + extraTotal).toFixed(2);
            totalDisplay.textContent = `${total} {{$general->site_currency}}`;
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
    });

    
        $(".payment_with_balance_btn").on('click',function(){
            let modal = $("#confirmationModal");
            modal.modal('show');
        });

                $(document).on('change', 'input[name="selected_gateway"]', function () {
            const actionUrl = $(this).data('action');
            $('#paymentForm').attr('action', actionUrl);
        });

    $('.delete-item').on('click',function(){
            let action = $(this).data('action');
            let modal = $('#deleteConfirmationModal');
            modal.find('form').attr('action',action);
            modal.modal('show');
        });

        $('.gateway-radio').each(function () {
        const $radio = $(this);

        // Default selected style on page load
        if ($radio.is(':checked')) {
            $radio.next('.gateway-card').addClass('selected');
        }

        // On change event
        $radio.on('change', function () {
            $('.gateway-card').removeClass('selected'); // Remove all
            if ($(this).is(':checked')) {
                $(this).next('.gateway-card').addClass('selected'); // Add selected
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.gateway-radio');
        const form = document.getElementById('paymentForm');

        radios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                const newAction = this.getAttribute('data-action');
                form.setAttribute('action', newAction);
            });
        });
    });
</script>