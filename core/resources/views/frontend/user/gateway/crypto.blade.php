@extends('frontend.layout.master2')

@section('content2')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card bg-second">
                <div class="card-header">
                    <h4>{{ __('Payment Information') }}</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Method Currency') }}</span>
                            <span>{{ $gateway->gateway_parameters->gateway_currency }}</span>
                        </li>

                        <li class="list-group-item  d-flex flex-wrap justify-content-between">
                            <span>{{ __('Wallet address :') }}</span>
                            <span class="w-100 d-flex justify-content-between">

                                <span class="col-md-10">
                                    <input value="{{ $gateway->gateway_parameters->account_number }}" id="copyClipboard"
                                        class="form-control">
                                </span>

                                <button class="copy-btn col-md-2" id="copyButton" onclick="copy()"><i
                                        class="far fa-copy"></i></button>
                            </span>

                            <div id="copied-success" class="copied">
                            <span>{{__('Copied')}}!</span>
                            </div>
                        </li>

                        <li class="list-group-item  d-flex justify-content-between">
                            <span>{{ __('Details For Payment') }}</span>
                            <span><?= $gateway->gateway_parameters->branch_name ?></span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card bg-second">
                <div class="card-header">
                    <h4>{{ __('Payment Information') }}</h4>
                </div>

                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item   d-flex justify-content-between">
                            <span>{{ __('Gateway Name') }}:</span>

                            <span>{{ $deposit->gateway->gateway_parameters->name }}</span>

                        </li>
                        <li class="list-group-item   d-flex justify-content-between">
                            <span>{{ __('Amount') }}:</span>
                            <span>{{ number_format($deposit->amount, 2) . ' ' . $general->site_currency }}</span>
                        </li>

                        <li class="list-group-item   d-flex justify-content-between">
                            <span>{{ __('Charge') }}:</span>
                            <span>{{ number_format($deposit->charge, 2) . ' ' . $general->site_currency }}</span>
                        </li>

                        <li class="list-group-item   d-flex justify-content-between">
                            <span>{{ __('Conversion Rate') }}:</span>
                            <span>{{ '1 ' . $general->site_currency . ' = ' . $deposit->rate . ' ' . $deposit->gateway->gateway_parameters->gateway_currency }}</span>
                        </li>

                        <li class="list-group-item   d-flex justify-content-between">
                            <span>{{ __('Total Payable Amount') }}:</span>
                            <span>{{ $deposit->final_amount . ' ' . $deposit->gateway->gateway_parameters->gateway_currency }}</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card bg-second">
                <div class="card-header">
                    <h4>{{ __('Payment Proof') }}</h4>
                </div>

                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            @foreach ($gateway->user_proof_param as $proof)
                                @if ($proof['type'] == 'text')
                                    <div class="form-group col-md-12">
                                        <label for="" class="mb-2 mt-2">{{ __($proof['field_name']) }}</label>
                                        <input type="text"
                                            name="{{ strtolower(str_replace(' ', '_', $proof['field_name'])) }}"
                                            class="form-control"
                                            {{ $proof['validation'] == 'required' ? 'required' : '' }}>
                                    </div>
                                @endif
                                @if ($proof['type'] == 'textarea')
                                    <div class="form-group col-md-12">
                                        <label for="" class="mb-2 mt-2">{{ __($proof['field_name']) }}</label>
                                        <textarea name="{{ strtolower(str_replace(' ', '_', $proof['field_name'])) }}" class="form-control"
                                            {{ $proof['validation'] == 'required' ? 'required' : '' }}></textarea>
                                    </div>
                                @endif

                                @if ($proof['type'] == 'file')
                                    <div class="form-group col-md-12">
                                        <label for="" class="mb-2 mt-2">{{ __($proof['field_name']) }}</label>
                                        <input type="file"
                                            name="{{ strtolower(str_replace(' ', '_', $proof['field_name'])) }}"
                                            class="form-control"
                                            {{ $proof['validation'] == 'required' ? 'required' : '' }}>
                                    </div>
                                @endif
                            @endforeach


                            <div class="form-group">
                                <button class="btn btn-primary mt-4"
                                    type="submit">{{ __('Send Proof For Payment ') }}</button>

                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            'use strict';

            function copy() {
                let copyText = document.getElementById("copyClipboard");
                let copySuccess = document.getElementById("copied-success");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(copyText.value);

                copySuccess.style.opacity = "1";
                setTimeout(function() {
                    copySuccess.style.opacity = "0"
                }, 500);
            }
        })
    </script>
@endpush

@push('style')
    <style>
        .clipboard {
            position: relative;
        }


        .copy-input {
            max-width: 275px;
            width: 100%;
            cursor: pointer;
            background-color: #eaeaeb;
            border: none;
            color: #6c6c6c;
            font-size: 14px;
            border-radius: 5px;
            padding: 15px 45px 15px 15px;
            font-family: 'Montserrat', sans-serif;
        }

        .copy-input:focus {
            outline: none;
        }

        .copy-btn {
            width: 40px;
            background-color: #eaeaeb;
            font-size: 18px;
            padding: 6px 9px;
            border-radius: 5px;
            border: none;
            color: #6c6c6c;
            margin-left: -50px;
            transition: all .4s;
        }

        .copy-btn:hover {
            transform: scale(1.3);
            color: #1a1a1a;
            cursor: pointer;
        }

        .copy-btn:focus {
            outline: none;
        }

        .copied {
            font-family: 'Montserrat', sans-serif;
            width: 100px;
            opacity: 0;
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            margin: auto;
            color: #000;
            padding: 15px 15px;
            background-color: #fff;
            border-radius: 5px;
            transition: .4s opacity;
        }
    </style>
@endpush
