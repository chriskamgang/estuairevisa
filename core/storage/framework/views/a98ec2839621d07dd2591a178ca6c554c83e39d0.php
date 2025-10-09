<?php $__env->startSection('content2'); ?>
    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-phone"></i> <?php echo e(__('Paiement Mobile Money')); ?>

                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <img src="<?php echo e(getFile('gateway', $gateway->gateway_image)); ?>"
                             alt="<?php echo e($gateway->gateway_name); ?>"
                             style="max-width: 150px; height: auto;">
                    </div>

                    <h3 class="text-success mb-3">
                        <i class="bi bi-check-circle"></i> <?php echo e(__('Paiement Initié')); ?>

                    </h3>

                    <div class="alert alert-info">
                        <h5><?php echo e(__('Montant à payer')); ?>: <strong><?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> FCFA</strong></h5>
                        <p class="mb-0"><?php echo e(__('Référence')); ?>: <code><?php echo e($payment->transaction_id); ?></code></p>
                    </div>

                    <div class="alert alert-warning my-4">
                        <h5 class="mb-3"><i class="bi bi-exclamation-triangle"></i> <?php echo e(__('Instructions')); ?></h5>
                        <p class="lead mb-3"><?php echo $instructions; ?></p>
                        <p class="mb-0"><?php echo e(__('Vous allez recevoir une notification sur votre téléphone pour valider le paiement.')); ?></p>
                    </div>

                    <div class="my-4">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden"><?php echo e(__('Vérification en cours...')); ?></span>
                        </div>
                        <p class="mt-3 text-muted"><?php echo e(__('Vérification automatique du paiement en cours...')); ?></p>
                    </div>

                    <div id="payment-status" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('script'); ?>
    <script>
        let checkCount = 0;
        const maxChecks = 60; // Vérifier pendant 5 minutes (60 x 5 secondes)

        const checkPaymentStatus = setInterval(function() {
            checkCount++;

            $.ajax({
                url: "<?php echo e(route('freemopay.check.status')); ?>",
                type: 'POST',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    transaction_id: "<?php echo e($payment->transaction_id); ?>"
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
                                <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-primary mt-3">${_('Retour au tableau de bord')}</a>
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
                        <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-primary mt-3">${_('Retour au tableau de bord')}</a>
                    </div>
                `);
            }
        }, 5000); // Vérifier toutes les 5 secondes

        function _(text) {
            return text; // Fonction de traduction simplifiée
        }
    </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/gateway/freemopay/waiting.blade.php ENDPATH**/ ?>