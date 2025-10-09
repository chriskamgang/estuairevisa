<?php $__env->startSection('content2'); ?>

<div class="card">
    <div class="card-header d-flex flex-wrap gap-3 justify-content-between align-items-center gap-2">
        <h5 class="mb-0"><?php echo e(__("My Applications")); ?></h5>
        <form action="">
            <div class="input-group">
                <input type="text" class="form-control form-control-sm" name="order_number"
                    value="<?php echo e(request()->order_number ?? ''); ?>" placeholder="<?php echo e(__('Track Number')); ?>">
                <button class="btn btn-sm btn-outline-secondary" type="submit"
                    id="button-addon2"><?php echo e(__("Search")); ?></button>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table responsive-table head-light-bg nir-table">
                <thead>
                    <tr>
                        <th><?php echo e(__("Track Number")); ?></th>
                        <th><?php echo e(__("Plan")); ?></th>
                        <th><?php echo e(__("Price")); ?></th>
                        <th><?php echo e(__("Status")); ?></th>
                        <th><?php echo e(__("Action")); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <p class="trx-number d-inline-flex align-items-center gap-2 mb-0">
                                <span class="text-sm copy-text"><?php echo e($item->order_number); ?></span>
                                <button type="button" class="copy-btn"><i class="bi bi-copy"></i></button>
                            </p>
                        </td>
                        <td><?php echo e($item->plan->title); ?></td>
                        <td><?php echo e(number_format($item->price,2)); ?></td>
                        <td>
                            <?= $item->status() ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('user.visa.details',$item->order_number)); ?>"
                                class="btn btn-sm btn-primary"><?php echo e(__("View")); ?> <i class="bi bi-chevron-right"></i></a>
                            <?php if($item->status == 'issues'): ?>
                            <a href="<?php echo e(route('user.visa.resubmit',$item->order_number)); ?>"
                                class="btn btn-sm btn-info"><?php echo e(__("Resubmit")); ?> <i class="bi bi-chevron-right"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center"><?php echo e(__("No data available")); ?></td>
                    </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>


    <?php if($items->hasPages()): ?>
    <?php echo e($items->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('script'); ?>
<script>
    $(function () {
        'use strict';

        $(".copy-btn").on('click', function (e) {
            e.preventDefault();

            let textToCopy = $(this).closest('.trx-number').find('.copy-text').text().trim();

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(textToCopy).then(function () {
                    iziToast.success({
                        message: "Copied: " + textToCopy,
                        position: 'topRight'
                    });
                }).catch(function (err) {
                    fallbackCopy(textToCopy);
                });
            } else {
                fallbackCopy(textToCopy);
            }

            function fallbackCopy(text) {
                let temp = $("<textarea>");
                $("body").append(temp);
                temp.val(text).select();

                try {
                    let success = document.execCommand("copy");
                    if (success) {
                        iziToast.success({
                            message: "Copied: " + text,
                            position: 'topRight'
                        });
                    } else {
                        throw new Error("Copy failed");
                    }
                } catch (err) {
                    iziToast.error({
                        message: "Failed to copy",
                        position: 'topRight'
                    });
                    console.error("Fallback copy failed", err);
                }

                temp.remove();
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('frontend.layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/user/visa/all.blade.php ENDPATH**/ ?>