<?php $__env->startSection('content2'); ?>


<div class="row g-3">
    <div class="col-6">
        <div class="nir-user-card style-balance">
            <span class="icon"><i class="bi bi-wallet2"></i></span>
            <div class="content">
                <p class="mb-0"><?php echo e(__("Balance")); ?></p>
                <h3 class="mb-0"><?php echo e(number_format($balance, 2)); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="nir-user-card style-deposit">
            <span class="icon"><i class="bi bi-bank"></i></span>
            <div class="content">
                <p class="mb-0"><?php echo e(__("Deposit Amount")); ?></p>
                <h3 class="mb-0"><?php echo e(number_format($total_deposit,2)); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="nir-user-card">
            <span class="icon"><i class="bi bi-rocket-takeoff"></i></span>
            <div class="content">
                <p class="mb-0"><?php echo e(__("Total Apply")); ?></p>
                <h3 class="mb-0"><?php echo e($total_applies); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="nir-user-card style-complete">
            <span class="icon"><i class="bi bi-check-circle"></i></span>
            <div class="content">
                <p class="mb-0"><?php echo e(__("Complete Apply")); ?></p>
                <h3 class="mb-0"><?php echo e($complete_applies); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="nir-user-card style-pending">
            <span class="icon"><i class="bi bi-arrow-repeat"></i></span>
            <div class="content">
                <p class="mb-0"><?php echo e(__("Processing Apply")); ?></p>
                <h3 class="mb-0"><?php echo e($processing_applies); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="nir-user-card style-pending">
            <span class="icon"><i class="bi bi-clock-history"></i></span>
            <div class="content">
                <p class="mb-0"><?php echo e(__("Shipped Apply")); ?></p>
                <h3 class="mb-0"><?php echo e($shipped_applies); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="nir-user-card style-pending">
            <span class="icon"><i class="bi bi-hourglass-split"></i></span>
            <div class="content">
                <p class="mb-0"><?php echo e(__("Payment Pending")); ?></p>
                <h3 class="mb-0"><?php echo e($pending_payment); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="nir-user-card style-ticket">
            <span class="icon"><i class="bi bi-ticket-perforated"></i></span>
            <div class="content">
                <p class="mb-0"><?php echo e(__("Total Tickets")); ?></p>
                <h3 class="mb-0"><?php echo e($total_ticket); ?></h3>
            </div>
        </div>
    </div>
</div>

<?php if($general->is_referral_active): ?>
<div class="mt-4">
    <label class="form-label"><?php echo e(__('Your refferal link')); ?></label>
    <div class="input-group mb-3">
        <input type="text" id="refer-link" class="form-control copy-text bg-white"
            value="<?php echo e(route('user.register', Auth::user()->username)); ?>" placeholder="referallink.com/refer"
            aria-label="Recipient's username" aria-describedby="basic-addon2" readonly>
        <button type="button" class="input-group-text refer-link-copy-btn btn btn-primary text-white" id="basic-addon2"><?php echo e(__('Copy')); ?></button>
    </div>
</div>
<?php endif; ?>


<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center gap-2">
        <h5 class="mb-0"><?php echo e(__("Latest Apply")); ?></h5>
        <a href="<?php echo e(route('user.visa.all')); ?>" class="text-primary fw-semibold text-sm"><?php echo e(__("View All")); ?> <i
                class="bi bi-chevron-right"></i></a>
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
                    <?php $__empty_1 = true; $__currentLoopData = $applies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                        <td class="text-center" colspan="5"><?php echo e(__("No data available")); ?></td>

                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
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
        $('.refer-link-copy-btn').on('click', function (e) {
            e.preventDefault();

            let input = $('.copy-text');
            let val = input.val();

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(val).then(function () {
                    iziToast.success({
                        message: "Copied refferal url",
                        position: 'topRight'
                    });
                }).catch(function (err) {
                    fallbackCopy(val);
                });
            } else {
                fallbackCopy(val);
            }

            function fallbackCopy(text) {
                let temp = $("<textarea>");
                $("body").append(temp);
                temp.val(text).select();

                try {
                    let success = document.execCommand("copy");
                    if (success) {
                        iziToast.success({
                            message: "Copied from input",
                            position: 'topRight'
                        });
                    } else {
                        throw new Error("Copy failed");
                    }
                } catch (err) {
                    iziToast.error({
                        message: "Failed to copy input",
                        position: 'topRight'
                    });
                   
                }

                temp.remove();
            }
        });

    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/user/dashboard.blade.php ENDPATH**/ ?>