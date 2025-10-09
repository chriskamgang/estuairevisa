<?php $__env->startSection('content2'); ?>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><?php echo e(__('Deposit Logs')); ?></h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table responsive-table head-light-bg nir-table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Trx')); ?></th>
                            <th><?php echo e(__('Gateway')); ?></th>
                            <th><?php echo e(__('Amount')); ?></th>
                            <th><?php echo e(__('Currency')); ?></th>
                            <th><?php echo e(__('Charge')); ?></th>
                            <th><?php echo e(__('Status')); ?></th>
                        </tr>
                    </thead>
    
                    <tbody>
    
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($transaction->transaction_id); ?> <br/>
                                <?php echo e($transaction->created_at->format('Y-m-d')); ?>

                                </td>
                                <td><?php echo e($transaction->gateway->gateway_name ?? 'Account Transfer'); ?></td>
                                <td><?php echo e(number_format($transaction->amount,2)); ?></td>
                                <td><?php echo e($transaction->gateway->gateway_parameters->gateway_currency); ?></td>
                                <td><?php echo e(number_format($transaction->charge,2) . ' ' . $transaction->currency); ?></td>
                                <td><?= $transaction->status() ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td class="text-center" colspan="100%">
                                    <?php echo e(__('No data Found')); ?>

                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
    
                <?php if($transactions->hasPages()): ?>
                    <?php echo e($transactions->links()); ?>

                <?php endif; ?>
    
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/user/deposit_log.blade.php ENDPATH**/ ?>