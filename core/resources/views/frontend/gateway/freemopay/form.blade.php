@extends('frontend.layout.master2')
@section('content2')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-credit-card"></i> {{ __('Paiement via') }} {{ $gateway->gateway_name }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ getFile('gateway', $gateway->gateway_image) }}"
                             alt="{{ $gateway->gateway_name }}"
                             style="max-width: 120px; height: auto;">
                    </div>

                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Montant') }}:</span>
                            <span class="fw-bold">{{ number_format($deposit->amount, 0, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Frais') }}:</span>
                            <span>{{ number_format($deposit->charge, 0, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">{{ __('Total à Payer') }}:</span>
                            <span class="fw-bold text-primary">{{ number_format($deposit->final_amount, 0, ',', ' ') }} FCFA</span>
                        </li>
                    </ul>

                    <form action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">
                                <i class="bi bi-telephone-fill"></i> {{ __('Numéro de téléphone') }}
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

                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> {{ __('Informations importantes') }}</h6>
                            <ul class="mb-0">
                                @if($gateway->gateway_name === 'Orange Money')
                                    <li>{{ __('Assurez-vous d\'avoir suffisamment de solde Orange Money') }}</li>
                                    <li>{{ __('Composez #150# pour vérifier votre solde') }}</li>
                                @else
                                    <li>{{ __('Assurez-vous d\'avoir suffisamment de solde MTN Mobile Money') }}</li>
                                    <li>{{ __('Composez *126# pour vérifier votre solde') }}</li>
                                @endif
                                <li>{{ __('Vous recevrez une notification pour valider le paiement') }}</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-phone"></i> {{ __('Procéder au Paiement') }}
                            </button>
                            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> {{ __('Annuler') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
