<div class="application-slide checkout-slide">
    <div class="application-slide-dialog">
        <div class="application-slide-content">
            <div class="application-slide-body">
                <div class="visa-progress-area">
                    <?php $__currentLoopData = ['Documents', 'Details', 'Review', 'Checkout']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div id="<?php echo e(strtolower($step)); ?>-progress"
                        class="single-progress <?php echo e($step === 'Checkout' ? 'active' : ''); ?>">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0"><?php echo e(__($step)); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
        
                <div class="mt-sm-5 mt-4">
                    <div id="checkout-wrapper">
                        <div class="row gy-4 justify-content-center">
                            <div class="col-lg-7">
                                <h6 class="mb-sm-4 mb-3"><?php echo e(__("ORDER SUMMARY")); ?></h6>
        
                                <?php
                                $items = $checkout_data['items'] ?? [];
                                $subtotal = collect($items)->sum(fn($item) => $item['plan']->price);
                                ?>
        
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__("Visa Type")); ?></th>
                                                <th><?php echo e(__("Visa for")); ?></th>
                                                <th><?php echo e(__("Price")); ?></th>
                                                <th><?php echo e(__("Remove")); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold text-nowrap"><?php echo e($data['plan']->title); ?></div>
                                                    <div class="text-muted small">
                                                        
                                                          <?php echo e(ucwords(str_replace('_', ' ', $data['plan']->plan_type))); ?>

                                                     
                                                    </div>
                                                </td>
                                                <td><?php echo e($data['plan']->country->name); ?></td>
                                                <td class="text-nowrap"><?php echo e(number_format($data['plan']->price, 2)); ?> <?php echo e($general->site_currency); ?></td>
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
                                                <td class="text-primary fw-semibold"><?php echo e(__("Subtotal :")); ?></td>
                                                <td colspan="3" class="text-end text-primary fw-semibold">
                                                    <?php echo e(number_format($subtotal, 2)); ?> <?php echo e($general->site_currency); ?>

                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
        
                                <div
                                    class="d-flex flex-wrap align-items-center justify-content-between text-lg text-primary fw-semibold mt-sm-4">
                                    <span>Total</span>
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
                                    <div class="checkout-payment-gateways">
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
                                    </div>
        
                                    <div class="d-flex align-items-center justify-content-center gap-3 mt-sm-5 mt-4">
                                        <a href="<?php echo e(route('home')); ?>" id="prev-review-btn flex-grow-1" class="btn btn-md btn-secondary">
                                            <i class="bi bi-chevron-left"></i> <?php echo e(__("Add New")); ?>

                                        </a>
        
                                        <?php if(auth()->guard()->check()): ?>
                                        <button class="btn btn-primary flex-grow-1" type="submit"><?php echo e(__("Place Order")); ?></button>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-md btn-primary flex-grow-1" data-bs-toggle="modal"
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


<style>
    .gateway-option input[type="radio"] {
        display: none;
    }

    .gateway-card {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .gateway-option input[type="radio"]:checked+.gateway-card {
        border-color: #0d6efd;
        /* Bootstrap Primary */
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
        background-color: #f0f8ff;
    }
</style>



<script>
    'use strict';
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.extra-fee-checkbox');
        const totalDisplay = document.getElementById('total-price');
        const baseSubtotal = parseFloat(`<?php echo e($subtotal); ?>`);

        function updateTotal() {
            let extraTotal = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    extraTotal += parseFloat(cb.dataset.amount);
                }
            });

            const total = (baseSubtotal + extraTotal).toFixed(2);
            totalDisplay.textContent = `${total} <?php echo e($general->site_currency); ?>`;
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
    });

    
        $(".payment_with_balance_btn").on('click',function(){
            let modal = $("#confirmationModal");
            modal.modal('show');
        });

                $(document).on('change', 'input[name="selected_gateway"]', function () {
            const actionUrl = $(this).data('action');
            $('#paymentForm').attr('action', actionUrl);
        });

    $('.delete-item').on('click',function(){
            let action = $(this).data('action');
            let modal = $('#deleteConfirmationModal');
            modal.find('form').attr('action',action);
            modal.modal('show');
        });

        $('.gateway-radio').each(function () {
        const $radio = $(this);

        // Default selected style on page load
        if ($radio.is(':checked')) {
            $radio.next('.gateway-card').addClass('selected');
        }

        // On change event
        $radio.on('change', function () {
            $('.gateway-card').removeClass('selected'); // Remove all
            if ($(this).is(':checked')) {
                $(this).next('.gateway-card').addClass('selected'); // Add selected
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.gateway-radio');
        const form = document.getElementById('paymentForm');

        radios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                const newAction = this.getAttribute('data-action');
                form.setAttribute('action', newAction);
            });
        });
    });
</script><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/form_modals/wrapper/checkout-wrapper.blade.php ENDPATH**/ ?>