@extends('frontend.layout.master2')

@section('content2')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h5>{{ __('Payment Preview') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">

                        <li class="list-group-item    d-flex justify-content-between">
                            <span>{{ __('Gateway Name') }}:</span>

                            <span>{{ $deposit->gateway->gateway_name }}</span>

                        </li>
                        <li class="list-group-item    d-flex justify-content-between">
                            <span>{{ __('Amount') }}:</span>
                            <span>{{ number_format($deposit->amount, 2) }}</span>
                        </li>

                        <li class="list-group-item    d-flex justify-content-between">
                            <span>{{ __('Charge') }}:</span>
                            <span>{{ number_format($deposit->charge, 2) }}</span>
                        </li>


                        <li class="list-group-item    d-flex justify-content-between">
                            <span>{{ __('Conversion Rate') }}:</span>
                            <span>{{ '1 ' . $general->site_currency . ' = ' . number_format($deposit->rate, 2) }}</span>
                        </li>



                        <li class="list-group-item    d-flex justify-content-between">
                            <span>{{ __('Total Payable Amount') }}:</span>
                            <span>{{ number_format($deposit->final_amount, 2) . ' ' . $deposit->gateway->gateway_parameters->gateway_currency }}</span>
                        </li>


                        <li class="list-group-item">

                            <button type="submit" class="btn btn-primary paystack"
                                data-amount="{{ number_format($deposit->final_amount, 2, '.', '') }}">{{ __('Pay With Paystack') }}</button>


                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    @php
        session()->forget('transaction_id');
        session()->put('transaction_id', $deposit->transaction_id);
    @endphp
@endsection


@push('script')
    <script src="https://js.paystack.co/v1/inline.js"></script>

    <script>
        $(function() {
            'use strict'
            $('.paystack').on('click', function(e) {
                e.preventDefault();
                payWithPaystack($(this).data('amount'))
            })

            function payWithPaystack(amount) {
                var handler = PaystackPop.setup({
                    key: "{{ $deposit->gateway->gateway_parameters->paystack_key }}", // Replace with your public key
                    email: "{{ auth()->user()->email }}",
                    amount: amount *
                        100, // the amount value is multiplied by 100 to convert to the lowest currency unit
                    currency: "{{ $deposit->gateway->gateway_parameters->gateway_currency }}", // Use GHS for Ghana Cedis or USD for US Dollars
                    ref: "{{ $deposit->transaction_id }}", // Replace with a reference you generated
                    callback: function(response) {

                        var reference = response.reference;

                        window.location = "{{ route('user.paystack.success') }}?reference=" + response
                            .reference
                    },
                    onClose: function() {
                        alert('Transaction was not completed, window closed.');
                    },
                });
                handler.openIframe();
            }
        })
    </script>
@endpush
