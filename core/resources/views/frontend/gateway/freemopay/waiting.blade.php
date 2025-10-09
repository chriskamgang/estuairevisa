@extends('frontend.layout.master2')
@section('content2')
    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-phone"></i> {{ __('Paiement Mobile Money') }}
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <img src="{{ getFile('gateway', $gateway->gateway_image) }}"
                             alt="{{ $gateway->gateway_name }}"
                             style="max-width: 150px; height: auto;">
                    </div>

                    <h3 class="text-success mb-3">
                        <i class="bi bi-check-circle"></i> {{ __('Paiement Initié') }}
                    </h3>

                    <div class="alert alert-info">
                        <h5>{{ __('Montant à payer') }}: <strong>{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</strong></h5>
                        <p class="mb-0">{{ __('Référence') }}: <code>{{ $payment->transaction_id }}</code></p>
                    </div>

                    <div class="alert alert-warning my-4">
                        <h5 class="mb-3"><i class="bi bi-exclamation-triangle"></i> {{ __('Instructions') }}</h5>
                        <p class="lead mb-3">{!! $instructions !!}</p>
                        <p class="mb-0">{{ __('Vous allez recevoir une notification sur votre téléphone pour valider le paiement.') }}</p>
                    </div>

                    <div class="my-4">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">{{ __('Vérification en cours...') }}</span>
                        </div>
                        <p class="mt-3 text-muted">{{ __('Vérification automatique du paiement en cours...') }}</p>
                    </div>

                    <div id="payment-status" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        let checkCount = 0;
        const maxChecks = 60; // Vérifier pendant 5 minutes (60 x 5 secondes)

        const checkPaymentStatus = setInterval(function() {
            checkCount++;

            $.ajax({
                url: "{{ route('freemopay.check.status') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    transaction_id: "{{ $payment->transaction_id }}"
                },
                success: function(response) {
                    if (response.status === 'SUCCESS') {
                        clearInterval(checkPaymentStatus);
                        $('#payment-status').html(`
                            <div class="alert alert-success">
                                <h4><i class="bi bi-check-circle-fill"></i> ${_('Paiement Réussi !')}</h4>
                                <p>${_('Votre paiement a été confirmé. Redirection en cours...')}</p>
                            </div>
                        `);
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 2000);
                    } else if (response.status === 'FAILED') {
                        clearInterval(checkPaymentStatus);
                        $('#payment-status').html(`
                            <div class="alert alert-danger">
                                <h4><i class="bi bi-x-circle-fill"></i> ${_('Paiement Échoué')}</h4>
                                <p>${response.message || _('Le paiement a échoué. Veuillez réessayer.')}</p>
                                <a href="{{ route('user.dashboard') }}" class="btn btn-primary mt-3">${_('Retour au tableau de bord')}</a>
                            </div>
                        `);
                    }
                },
                error: function() {
                    console.log('Erreur de vérification du statut');
                }
            });

            // Arrêter après le nombre max de vérifications
            if (checkCount >= maxChecks) {
                clearInterval(checkPaymentStatus);
                $('#payment-status').html(`
                    <div class="alert alert-warning">
                        <h4><i class="bi bi-hourglass-split"></i> ${_('Vérification Expirée')}</h4>
                        <p>${_('Le délai de vérification automatique est dépassé. Veuillez vérifier votre compte ou contacter le support.')}</p>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary mt-3">${_('Retour au tableau de bord')}</a>
                    </div>
                `);
            }
        }, 5000); // Vérifier toutes les 5 secondes

        function _(text) {
            return text; // Fonction de traduction simplifiée
        }
    </script>
    @endpush
@endsection
