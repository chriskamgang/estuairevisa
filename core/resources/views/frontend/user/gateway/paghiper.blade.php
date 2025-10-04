@extends('frontend.layout.master2')

@section('content2')
    <div class="row gy-4">
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
                            <span>{{ number_format($deposit->final_amount, 2).' '. $deposit->gateway->gateway_parameters->gateway_currency }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <form action="" method="POST">
                        @csrf
                        <input type="hidden" name="amount" value="{{ number_format($deposit->final_amount, 2) }}">

                        <div class="form-group">
                            <label for="">{{ __('CPF OR CNPJ') }}</label>
                            <input type="text" name="cpf" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary mt-5">{{ __('Pay With Paghiper') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection