@extends('frontend.layout.master2')
@section('content2')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('Transaction Logs') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table responsive-table head-light-bg nir-table">
                    <thead>
                        <tr>
                            <th>{{ __('Trx / Date') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Gateway') }}</th>
                            <th>{{ __('Details') }}</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        @forelse($transactions as $key => $transaction)
                            <tr>
                                <td>
                                    {{ $transaction->trx }}
                                    <p class="mb-0 text-sm">{{ $transaction->created_at->format('Y-m-d') }}</p>
                                </td>
                                <td>
                                    <p class="mb-0">
                                        {{ $transaction->amount }}
                                        {{ $transaction->currency }}
                                    </p>
                                    <p class="text-sm mb-0 text-danger">{{ $transaction->charge . ' ' . $transaction->currency }}</p>
                                </td>
                                <td>
                                    {{ $transaction->user->fname . ' ' . $transaction->user->lname }}</td>
                                <td>
                                    {{ $transaction->gateway->gateway_name ?? 'Account Transfer' }}</td>
                               
                                <td>{{ $transaction->details }}</td>
                            </tr>
                        @empty
    
    
                            <tr>
                                <td class="text-center" colspan="100%">
                                    <div class="py-5">
                                        <span class="no-data-icon"><i class="bi bi-emoji-frown"></i></span>
                                        <p class="mb-0">{{ __('No Data Found') }}</p>
                                    </div>
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
