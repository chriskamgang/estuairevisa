<?php $__env->startSection('frontend_content'); ?>



<?php
    $breadcrumb = content('breadcrumb.content');
?>

<section class="breadcrumbs"
    style="background-image: url(<?php echo e(getFile('breadcrumb', @$breadcrumb->data->backgroundimage)); ?>);">
    <div class="container">
        <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center text-capitalize">
            <h2><?php echo e(__("Cart")); ?></h2>
            <ol>
                <li><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
                <li><?php echo e(__("Cart")); ?></li>
            </ol>
        </div>
    </div>
</section>



<div class=" checkout-slide">
    <div class="application-slide-inner p-4">
        <div class="visa-progress-area">
            <?php $__currentLoopData = ['Documents', 'Details', 'Review', 'Checkout']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="<?php echo e(strtolower($step)); ?>-progress"
                class="single-progress <?php echo e($step === 'Checkout' ? 'active' : ''); ?>">
                <span class="circle"><i class="bi bi-check2"></i></span>
                <p class="mb-0"><?php echo e(__($step)); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="container mt-5">
            <div id="checkout-wrapper">
                <div class="row gy-4 justify-content-center">
                    <div class="col-lg-7">
                        <h6 class="mb-4"><?php echo e(__("ORDER SUMMARY")); ?></h6>

                        <?php
                        $items = $checkout_data['items'] ?? [];
                        $subtotal = collect($items)->sum(fn($item) => $item['plan']->price);
                        ?>

                        <div class="table-responsive">
                            <table class="table responsive-table">
                            <thead>
                                <tr>
                                    <th><?php echo e(__("Visa Type")); ?></th>
                                    <th><?php echo e(__("Visa For")); ?></th>
                                    <th><?php echo e(__("Price")); ?></th>
                                    <th><?php echo e(__("Remove")); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($data['plan']->title); ?></div>
                                        <div class="text-muted small">
                                            <?php echo e(ucwords(str_replace('_', ' ', $data['plan']->plan_type))); ?>

                                        </div>
                                    </td>
                                    <td><?php echo e($data['plan']->country->name); ?></td>
                                    <td><?php echo e(number_format($data['plan']->price, 2)); ?> <?php echo e($general->site_currency); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('visa.cart.remove',$trx)); ?>" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <?php echo e(__("No items in your order.")); ?>

                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-primary fw-semibold"><?php echo e(__("Subtotal")); ?></td>
                                    <td colspan="3" class="text-end text-primary fw-semibold">
                                        <?php echo e(number_format($subtotal, 2)); ?> <?php echo e($general->site_currency); ?>

                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        </div>

                        <div
                            class="d-flex flex-wrap align-items-center justify-content-between text-lg text-primary fw-semibold mt-4">
                            <span><?php echo e(__("Total")); ?></span>
                            <span id="total-price"><?php echo e(number_format($subtotal, 2)); ?> <?php echo e($general->site_currency); ?></span>
                        </div>


                    </div>
                    <div class="col-lg-5">
                        <?php if(auth()->guard()->check()): ?>
                        <button class="btn btn-primary w-100 mb-3 payment_with_balance_btn"><?php echo e(__("Payment with
                            balance")); ?></button>
                        <?php endif; ?>
                        <h6 class="mb-4"><?php echo e(__("Payment Gateways")); ?></h6>
                        <form id="paymentForm" action="<?php echo e(route('user.paynow', $gateways[0] ? $gateways[0]->id : 0)); ?>"
                            method="POST" class="submit_form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="amount" value="<?php echo e($subtotal); ?>">
                            <div class="row g-3">
                                <?php $gatewayIndex = 0; ?>
                                <?php $__currentLoopData = $gateways ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-12">
                                    <label class="w-100 gateway-option">
                                        <input type="radio" name="selected_gateway" value="<?php echo e($gateway->id); ?>"
                                            class="gateway-radio" data-action="<?php echo e(route('user.paynow', $gateway->id)); ?>"
                                            <?php echo e($gatewayIndex===0 ? 'checked' : ''); ?>>
                                        <div class="gateway-card">
                                            <img src="<?php echo e(getFile('gateways', $gateway->gateway_image)); ?>"
                                                alt="<?php echo e($gateway->name); ?>">
                                            <div class="gateway-content">
                                                <h4 class="title">
                                                    <?php echo e($gateway->gateway_name === 'bank' ?
                                                    $gateway->gateway_parameters->name : $gateway->gateway_name); ?>

                                                </h4>
                                                <h4 class="subtitle"><?php echo e(__("Amount")); ?> <?php echo e($subtotal); ?>

                                                    <?php echo e($general->site_currency); ?></h4>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <?php $gatewayIndex++; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="d-flex gap-2 align-items-center justify-content-center gap-3 mt-5">
                                <a href="<?php echo e(route('home')); ?>" id="prev-review-btn" class="btn btn-secondary flex-grow-1">
                                    <i class="bi bi-chevron-left"></i> <?php echo e(__("Add New")); ?>

                                </a>

                                <?php if(auth()->guard()->check()): ?>
                                <button class="btn btn-primary flex-grow-1 placeorder_btn" type="submit"><?php echo e(__("Place
                                    Order")); ?></button>
                                <?php else: ?>
                                <button type="button" class="btn btn-primary flex-grow-1" data-bs-toggle="modal"
                                    data-bs-target="#authModal">
                                    <?php echo e(__("Login / Register")); ?>

                                </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if(auth()->guard()->guest()): ?>
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <ul class="nav nav-tabs w-100 justify-content-center border-0" id="authTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab"
                            data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane"
                            aria-selected="true">
                            <?php echo e(__("Login")); ?>

                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab"
                            data-bs-target="#register-tab-pane" type="button" role="tab"
                            aria-controls="register-tab-pane" aria-selected="false">
                            <?php echo e(__("Register")); ?>

                        </button>
                    </li>
                </ul>
                <button type="button" class="btn-close position-absolute end-0 me-3 mt-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body tab-content px-4 pb-4 pt-2" id="authTabContent">

                <div class="tab-pane fade show active" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab">
                    <form action="<?php echo e(route('user.login')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="login-email" class="form-label"><?php echo e(__("Email")); ?></label>
                            <input type="email" class="form-control" name="email" id="login-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="login-password" class="form-label"><?php echo e(__("Password")); ?></label>
                            <input type="password" class="form-control" name="password" id="login-password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary"><?php echo e(__("Login")); ?></button>
                        </div>
                    </form>
                </div>


                <div class="tab-pane fade" id="register-tab-pane" role="tabpanel" aria-labelledby="register-tab">
                    <form action="<?php echo e(route('user.register')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="place_order" value="1">
                        <div class="row gy-4">
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('First Name')); ?></label>
                                <input type="text" class="form-control" name="fname" value="<?php echo e(old('fname')); ?>"
                                    id="first_name" placeholder="<?php echo e(__('First Name')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('Last Name')); ?></label>
                                <input type="text" class="form-control" name="lname" value="<?php echo e(old('lname')); ?>"
                                    id="last_name" placeholder="<?php echo e(__('Last name')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('Username')); ?></label>
                                <input type="text" class="form-control" name="username" value="<?php echo e(old('username')); ?>"
                                    id="username" placeholder="<?php echo e(__('User Name')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('Phone')); ?></label>
                                <input type="text" class="form-control" name="phone" value="<?php echo e(old('phone')); ?>"
                                    id="phone" placeholder="<?php echo e(__('Phone')); ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label"><?php echo e(__('Email')); ?></label>
                                <input type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>"
                                    id="email" placeholder="<?php echo e(__('Email')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('Password')); ?></label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="<?php echo e(__('Password')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?php echo e(__('Confirm Password')); ?></label>
                                <input type="password" class="form-control" name="password_confirmation"
                                    id="password_confirmation" placeholder="<?php echo e(__('Confirm Password')); ?>">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100"><?php echo e(__("Register")); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if(auth()->guard()->check()): ?>
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="<?php echo e(route('user.visa.payment')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h4 class="header-title"><?php echo e(__("Payment with balance")); ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p><?php echo e(__("Are you sure you want to proceed with this payment using your balance?")); ?></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__("Cancel")); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(__("Confirm Payment")); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php endif; ?>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('script'); ?>
<script>
    $(function(){

        'use strict'
        $(document).on('change', 'input[name="selected_gateway"]', function () {
            const actionUrl = $(this).data('action');
            $('#paymentForm').attr('action', actionUrl);
        });


        $(".payment_with_balance_btn").on('click',function(){
            let modal = $("#confirmationModal");
            modal.modal('show');
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('frontend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/pages/cart.blade.php ENDPATH**/ ?>