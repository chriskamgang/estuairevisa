@extends('frontend.layout.master2')
@section('content2')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('Payment Logs') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table responsive-table head-light-bg nir-table">
                    <thead>
                        <tr>
                            <th>{{ __('Trx') }}</th>
                            <th>{{ __('Gateway') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Currency') }}</th>
                            <th>{{ __('Charge') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
    
                    <tbody>
    
                        @forelse($transactions as $key => $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_id }} <br/>
                                {{ $transaction->created_at->format('Y-m-d') }}
                                </td>
                                <td>{{ $transaction->gateway->gateway_name ?? 'Account Transfer' }}</td>
                                <td>{{ number_format($transaction->amount,2) }}</td>
                                <td>{{ $transaction->gateway->gateway_parameters->gateway_currency }}</td>
                                <td>{{ number_format($transaction->charge,2) . ' ' . $transaction->currency }}</td>
                                <td><?= $transaction->status() ?></td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">
                                    {{ __('No data Found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
    
                @if ($transactions->hasPages())
                    {{ $transactions->links() }}
                @endif
    
            </div>
        </div>
    </div>
@endsection
