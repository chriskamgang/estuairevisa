@extends('frontend.layout.master2')
@section('content2')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #FF7900 0%, #FF9933 100%);">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-phone"></i> {{ __('Paiement Orange Money') }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ getFile('gateway', $gateway->gateway_image) }}"
                             alt="Orange Money"
                             style="max-width: 150px; height: auto;">
                    </div>

                    <div class="alert alert-warning">
                        <h5><i class="bi bi-exclamation-triangle"></i> {{ __('Instructions de Paiement') }}</h5>
                        <ol class="mb-0">
                            <li>{{ __('Composez #150# sur votre téléphone Orange Money') }}</li>
                            <li>{{ __('Sélectionnez "Transfert d\'argent"') }}</li>
                            <li>{{ __('Envoyez') }} <strong>{{ number_format($payment->final_amount, 0, ',', ' ') }} FCFA</strong> {{ __('au numéro:') }}
                                <h3 class="text-danger mt-2 mb-2">{{ $gatewayParams->merchant_phone }}</h3>
                            </li>
                            <li>{{ __('Notez le numéro de transaction reçu par SMS') }}</li>
                            <li>{{ __('Remplissez le formulaire ci-dessous avec le numéro de transaction et une capture d\'écran') }}</li>
                        </ol>
                    </div>

                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Montant') }}:</span>
                            <span class="fw-bold">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Frais') }}:</span>
                            <span>{{ number_format($payment->charge, 0, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">{{ __('Total à Payer') }}:</span>
                            <span class="fw-bold text-primary">{{ number_format($payment->final_amount, 0, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Référence') }}:</span>
                            <span><code>{{ $payment->transaction_id }}</code></span>
                        </li>
                    </ul>

                    <form action="{{ route('orangemoney.submit.proof') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="transaction_id" value="{{ $payment->transaction_id }}">

                        <div class="mb-3">
                            <label for="payment_reference" class="form-label">
                                <i class="bi bi-hash"></i> {{ __('Numéro de Transaction Orange Money') }} *
                            </label>
                            <input type="text"
                                   name="payment_reference"
                                   id="payment_reference"
                                   class="form-control @error('payment_reference') is-invalid @enderror"
                                   placeholder="Ex: OM123456789"
                                   required>
                            @error('payment_reference')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="proof_image" class="form-label">
                                <i class="bi bi-camera"></i> {{ __('Capture d\'écran du Paiement') }} *
                            </label>
                            <input type="file"
                                   name="proof_image"
                                   id="proof_image"
                                   class="form-control @error('proof_image') is-invalid @enderror"
                                   accept="image/jpeg,image/jpg,image/png"
                                   required>
                            <small class="form-text text-muted">
                                {{ __('Formats acceptés: JPG, JPEG, PNG. Taille max: 2 Mo') }}
                            </small>
                            @error('proof_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            {{ __('Votre paiement sera validé manuellement par notre équipe dans les plus brefs délais.') }}
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> {{ __('Soumettre la Preuve de Paiement') }}
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
