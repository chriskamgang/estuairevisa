<?php $__env->startSection('content2'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center" style="background: linear-gradient(135deg, #FF7900 0%, #FF9933 100%);">
                    <h5 class="text-white"><?php echo e(__('Orange Money Payment')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="<?php echo e(getFile('gateway', $gateway->gateway_image)); ?>"
                             alt="Orange Money"
                             style="max-width: 150px; height: auto;">
                    </div>

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?php echo e(__('Gateway Name')); ?>:</span>
                            <span><?php echo e($deposit->gateway->gateway_name); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?php echo e(__('Amount')); ?>:</span>
                            <span><?php echo e(number_format($deposit->amount, 0, ',', ' ')); ?> FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?php echo e(__('Charge')); ?>:</span>
                            <span><?php echo e(number_format($deposit->charge, 0, ',', ' ')); ?> FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?php echo e(__('Conversion Rate')); ?>:</span>
                            <span><?php echo e('1 ' . $general->site_currency . ' = ' . number_format($deposit->rate, 2)); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold"><?php echo e(__('Total Payable Amount')); ?>:</span>
                            <span class="fw-bold text-primary"><?php echo e(number_format($deposit->final_amount, 0, ',', ' ') . ' FCFA'); ?></span>
                        </li>
                    </ul>

                    <div class="alert alert-info mt-4">
                        <h6><i class="bi bi-info-circle"></i> <?php echo e(__('Instructions')); ?></h6>
                        <ul class="mb-0">
                            <li><?php echo e(__('Entrez votre numéro Orange Money (format: 237XXXXXXXXX)')); ?></li>
                            <li><?php echo e(__('Vous recevrez une notification pour valider le paiement sur votre téléphone')); ?></li>
                            <li><?php echo e(__('Composez #150# pour vérifier votre solde Orange Money')); ?></li>
                        </ul>
                    </div>

                    <form action="<?php echo e(url('gateways/' . $gateway->id . '/details')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">
                                <i class="bi bi-telephone-fill"></i> <?php echo e(__('Numéro Orange Money')); ?> *
                            </label>
                            <input type="text"
                                   name="phone_number"
                                   id="phone_number"
                                   class="form-control <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="237695509408"
                                   value="<?php echo e(old('phone_number')); ?>"
                                   required>
                            <small class="form-text text-muted">
                                <?php echo e(__('Format: 237XXXXXXXXX (12 chiffres)')); ?>

                            </small>
                            <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <input type="hidden" name="amount" value="<?php echo e(number_format($deposit->final_amount, 2)); ?>">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-phone"></i> <?php echo e(__('Pay With Orange Money')); ?>

                            </button>
                            <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> <?php echo e(__('Cancel')); ?>

                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/user/gateway/orangemoney.blade.php ENDPATH**/ ?>