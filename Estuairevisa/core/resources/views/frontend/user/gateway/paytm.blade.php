@extends('frontend.layout.master2')

@section('content2')
    <section class="breadcrumbs" style="background-image: url({{ getFile('breadcrumbs', $general->breadcrumbs) }});">
        <div class="container">

            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center text-capitalize">
                <h2>{{ __($pageTitle ?? '') }}</h2>
                <ol>
                    <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li>{{ __($pageTitle ?? '') }}</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="py-100">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>{{ __('Payment Preview') }}</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                              
                                <li class="list-group-item   text-white d-flex justify-content-between">
                                    <span>{{ __('Gateway Name') }}:</span>

                                    <span>{{ $deposit->gateway->gateway_name }}</span>

                                </li>
                                <li class="list-group-item   text-white d-flex justify-content-between">
                                    <span>{{ __('Amount') }}:</span>
                                    <span>{{ number_format($deposit->amount, 2) }}</span>
                                </li>

                                <li class="list-group-item  text-white  d-flex justify-content-between">
                                    <span>{{ __('Charge') }}:</span>
                                    <span>{{ number_format($deposit->charge, 2) }}</span>
                                </li>


                                <li class="list-group-item  text-white  d-flex justify-content-between">
                                    <span>{{ __('Conversion Rate') }}:</span>
                                    <span>{{ '1 ' . $general->site_currency . ' = ' . number_format($deposit->rate, 2) }}</span>
                                </li>



                                <li class="list-group-item   text-white d-flex justify-content-between">
                                    <span>{{ __('Total Payable Amount') }}:</span>
                                    <span>{{ number_format($deposit->final_amount, 2) }}</span>
                                </li>


                                <li class="list-group-item  text-white">
                                    <form action="" method="POST">
                                        @csrf
                                        <input type="hidden" name="amount"
                                            value="{{ number_format($deposit->final_amount, 2) }}">
                                        <button type="submit" class="btn btn-primary">{{ __('Pay With PayTm') }}</button>

                                    </form>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
