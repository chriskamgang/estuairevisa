<?php $__env->startSection('content2'); ?>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><?php echo e(__('Transaction Logs')); ?></h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table responsive-table head-light-bg nir-table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Trx / Date')); ?></th>
                            <th><?php echo e(__('Amount')); ?></th>
                            <th><?php echo e(__('User')); ?></th>
                            <th><?php echo e(__('Gateway')); ?></th>
                            <th><?php echo e(__('Details')); ?></th>
                        </tr>
                    </thead>
    
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <?php echo e($transaction->trx); ?>

                                    <p class="mb-0 text-sm"><?php echo e($transaction->created_at->format('Y-m-d')); ?></p>
                                </td>
                                <td>
                                    <p class="mb-0">
                                        <?php echo e($transaction->amount); ?>

                                        <?php echo e($transaction->currency); ?>

                                    </p>
                                    <p class="text-sm mb-0 text-danger"><?php echo e($transaction->charge . ' ' . $transaction->currency); ?></p>
                                </td>
                                <td>
                                    <?php echo e($transaction->user->fname . ' ' . $transaction->user->lname); ?></td>
                                <td>
                                    <?php echo e($transaction->gateway->gateway_name ?? 'Account Transfer'); ?></td>
                               
                                <td><?php echo e($transaction->details); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    
    
                            <tr>
                                <td class="text-center" colspan="100%">
                                    <div class="py-5">
                                        <span class="no-data-icon"><i class="bi bi-emoji-frown"></i></span>
                                        <p class="mb-0"><?php echo e(__('No Data Found')); ?></p>
                                    </div>
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

<?php echo $__env->make('frontend.layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/user/transaction.blade.php ENDPATH**/ ?>