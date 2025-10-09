<?php $__env->startSection('content2'); ?>
<div class="row gy-4 justify-content-center">
    <?php if(isset($type) && $type === 'deposit'): ?>
        <?php $__empty_1 = true; $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-xl-4 col-sm-6">
                <div class="payment-box text-center">
                    <div class="payment-box-thumb">
                        <img src="<?php echo e(getFile('gateways', $gateway->gateway_image)); ?>" alt="Gateway Image" class="trans-img">
                    </div>
                    <div class="payment-box-content">
                        <h6 class="title">
                            <?php echo e($gateway->gateway_name === 'bank' ? $gateway->gateway_parameters->name : $gateway->gateway_name); ?>

                        </h6>
                        <button
                            type="button"
                            class="btn btn-md btn-primary w-100 paynow mt-3"
                            data-href="<?php echo e(route('user.paynow', $gateway->id)); ?>"
                            data-id="<?php echo e($gateway->id); ?>"
                        >
                            <?php echo e(__('Pay Now')); ?>

                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center">
                <?php echo e(__('No gateways available.')); ?>

            </div>
        <?php endif; ?>
    <?php else: ?>
        <?php $__empty_1 = true; $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-xl-4 col-sm-6">
                <form action="<?php echo e(route('user.paynow', $gateway->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($gateway->id); ?>">
                    <input type="hidden" name="amount" value="<?php echo e($total_amount); ?>">
                    <div class="payment-box text-center">
                        <div class="payment-box-thumb">
                            <img src="<?php echo e(getFile('gateways', $gateway->gateway_image)); ?>" alt="Gateway Image" class="trans-img">
                        </div>
                        <div class="payment-box-content">
                            <h4 class="title">
                                <?php echo e($gateway->gateway_name === 'bank' ? $gateway->gateway_parameters->name : $gateway->gateway_name); ?>

                            </h4>
                            <h4 class="title"><?php echo e(__("Amount")); ?> <?php echo e(number_format($total_amount)); ?></h4>
                            <button type="submit" class="btn btn-md btn-primarys w-100 mt-3">
                                <?php echo e(__('Pay Now')); ?>

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 text-center">
                <?php echo e(__('No gateways available.')); ?>

            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php if(isset($type) && $type === 'deposit'): ?>
<!-- Deposit Modal -->
<div class="modal fade" id="paynow" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="">
            <?php echo csrf_field(); ?>
            <div class="modal-content bg-body">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('Deposit Amount')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id">
                    <input type="hidden" name="user_id" value="<?php echo e(auth()->id()); ?>">
                    <input type="hidden" name="type" value="deposit">

                    <div class="form-group mb-3">
                        <label for="amount"><?php echo e(__('Amount')); ?></label>
                        <input
                            type="text"
                            name="amount"
                            class="form-control"
                            placeholder="<?php echo e(__('Enter Amount')); ?>"
                            required
                        >
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <?php echo e(__('Close')); ?>

                    </button>
                    <button type="submit" class="btn btn-primary">
                        <?php echo e(__('Deposit Now')); ?>

                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
    $(function () {
        'use strict';

        // Open modal and populate form action and hidden id
        $('.paynow').on('click', function () {
            const modal = $('#paynow');
            modal.find('form').attr('action', $(this).data('href'));
            modal.find('input[name="id"]').val($(this).data('id'));
            modal.modal('show');
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('frontend.layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/user/gateway/gateways.blade.php ENDPATH**/ ?>