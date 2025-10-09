@extends('frontend.layout.master2')

@section('content2')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center" style="background: linear-gradient(135deg, #FFCC00 0%, #FFD700 100%);">
                    <h5 class="text-dark">{{ __('MTN Mobile Money Payment') }}</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ getFile('gateway', $gateway->gateway_image) }}"
                             alt="MTN Mobile Money"
                             style="max-width: 150px; height: auto;">
                    </div>

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Gateway Name') }}:</span>
                            <span>{{ $deposit->gateway->gateway_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Amount') }}:</span>
                            <span>{{ number_format($deposit->amount, 0, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Charge') }}:</span>
                            <span>{{ number_format($deposit->charge, 0, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Conversion Rate') }}:</span>
                            <span>{{ '1 ' . $general->site_currency . ' = ' . number_format($deposit->rate, 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">{{ __('Total Payable Amount') }}:</span>
                            <span class="fw-bold text-primary">{{ number_format($deposit->final_amount, 0, ',', ' ') . ' FCFA' }}</span>
                        </li>
                    </ul>

                    <div class="alert alert-warning mt-4">
                        <h6><i class="bi bi-info-circle"></i> {{ __('Instructions') }}</h6>
                        <ul class="mb-0">
                            <li>{{ __('Entrez votre numéro MTN Mobile Money (format: 237XXXXXXXXX)') }}</li>
                            <li>{{ __('Vous recevrez une notification pour valider le paiement sur votre téléphone') }}</li>
                            <li>{{ __('Composez *126# pour vérifier votre solde MTN Mobile Money') }}</li>
                        </ul>
                    </div>

                    <form action="{{ url('gateways/' . $gateway->id . '/details') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">
                                <i class="bi bi-telephone-fill"></i> {{ __('Numéro MTN Mobile Money') }} *
                            </label>
                            <input type="text"
                                   name="phone_number"
                                   id="phone_number"
                                   class="form-control @error('phone_number') is-invalid @enderror"
                                   placeholder="237695509408"
                                   value="{{ old('phone_number') }}"
                                   required>
                            <small class="form-text text-muted">
                                {{ __('Format: 237XXXXXXXXX (12 chiffres)') }}
                            </small>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <input type="hidden" name="amount" value="{{ number_format($deposit->final_amount, 2) }}">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg text-dark">
                                <i class="bi bi-phone"></i> {{ __('Pay With MTN Mobile Money') }}
                            </button>
                            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
