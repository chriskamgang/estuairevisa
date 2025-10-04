@extends('frontend.layout.master2')
@section('content2')
<div class="row gy-4 justify-content-center">
    @if (isset($type) && $type === 'deposit')
        @forelse ($gateways as $gateway)
            <div class="col-xl-4 col-sm-6">
                <div class="payment-box text-center">
                    <div class="payment-box-thumb">
                        <img src="{{ getFile('gateways', $gateway->gateway_image) }}" alt="Gateway Image" class="trans-img">
                    </div>
                    <div class="payment-box-content">
                        <h6 class="title">
                            {{ $gateway->gateway_name === 'bank' ? $gateway->gateway_parameters->name : $gateway->gateway_name }}
                        </h6>
                        <button
                            type="button"
                            class="btn btn-md btn-primary w-100 paynow mt-3"
                            data-href="{{ route('user.paynow', $gateway->id) }}"
                            data-id="{{ $gateway->id }}"
                        >
                            {{ __('Pay Now') }}
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                {{ __('No gateways available.') }}
            </div>
        @endforelse
    @else
        @forelse ($gateways as $gateway)
            <div class="col-xl-4 col-sm-6">
                <form action="{{ route('user.paynow', $gateway->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $gateway->id }}">
                    <input type="hidden" name="amount" value="{{$total_amount}}">
                    <div class="payment-box text-center">
                        <div class="payment-box-thumb">
                            <img src="{{ getFile('gateways', $gateway->gateway_image) }}" alt="Gateway Image" class="trans-img">
                        </div>
                        <div class="payment-box-content">
                            <h4 class="title">
                                {{ $gateway->gateway_name === 'bank' ? $gateway->gateway_parameters->name : $gateway->gateway_name }}
                            </h4>
                            <h4 class="title">{{ __("Amount") }} {{ number_format($total_amount) }}</h4>
                            <button type="submit" class="btn btn-md btn-primarys w-100 mt-3">
                                {{ __('Pay Now') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @empty
            <div class="col-12 text-center">
                {{ __('No gateways available.') }}
            </div>
        @endforelse
    @endif
</div>

@if (isset($type) && $type === 'deposit')
<!-- Deposit Modal -->
<div class="modal fade" id="paynow" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="">
            @csrf
            <div class="modal-content bg-body">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Deposit Amount') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="type" value="deposit">

                    <div class="form-group mb-3">
                        <label for="amount">{{ __('Amount') }}</label>
                        <input
                            type="text"
                            name="amount"
                            class="form-control"
                            placeholder="{{ __('Enter Amount') }}"
                            required
                        >
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ __('Deposit Now') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('script')
<script>
    $(function () {
        'use strict';

        // Open modal and populate form action and hidden id
        $('.paynow').on('click', function () {
            const modal = $('#paynow');
            modal.find('form').attr('action', $(this).data('href'));
            modal.find('input[name="id"]').val($(this).data('id'));
            modal.modal('show');
        });
    });
</script>
@endpush