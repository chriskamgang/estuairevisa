<div class="application-slide review-slide">
    <div class="application-slide-dialog">
        <div class="application-slide-content">
            <div class="application-slide-body">
                <div class="visa-progress-area">
                    <div id="document-progress" class="single-progress">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0"><?php echo e(__('Documents')); ?></p>
                    </div>
                    <div id="details-progress" class="single-progress ">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0"><?php echo e(__('Details')); ?></p>
                    </div>
                    <div id="review-progress" class="single-progress active">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0"><?php echo e(__('Review')); ?></p>
                    </div>
                    <div id="checkout-progress" class="single-progress ">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0"><?php echo e(__('Checkout')); ?></p>
                    </div>
                </div>
        
                <div class="mt-sm-5 mt-4">
                    <div id="review-wrapper">
                        <div class="d-flex flex-wrap align-items-center gap-2 justify-content-sm-between justify-content-center mb-sm-5 mb-3">
                            <p class="text-xl text-uppercase text-primary mb-0 plan_name"><?php echo e($plan->title); ?> <span class="visa-package-country text-nowrap"> <?php echo e(__('Visa for:')); ?> <?php echo e($plan->country->name); ?></span> </p>
                            <p class="mb-0 d-flex align-items-center gap-2 justify-content-end"><?php echo e(__('Price')); ?>: <b
                                    class="text-dark text-xl plan_price"><?php echo e(number_format($plan->price, 2)." ".$general->site_currency); ?></b></p>
                        </div>
        
                        <div class="row gy-sm-4 gy-3">
                            <div class="col-lg-6">
                                <label class="form-label"><?php echo e(__("Change Visa Type?")); ?></label>
                                <select name="plan_id" class="form-select bg-white">
                                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == $plan->id ? 'selected' : ''); ?>

                                        data-action="<?php echo e(route('visa.plan.change',$item->id)); ?>" data-duration="<?php echo e($item->heading); ?>" data-title="<?php echo e($item->title); ?>" data-description="<?php echo e($item->short_description); ?>" data-country="<?php echo e($item->country->name); ?>" ><?php echo e($item->title); ?>

                                        - <?php echo e(number_format($item->price,2)." ".$general->site_currency); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="">
                                    <ul class="list-group">
                                        
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Plan Name")); ?>:</span>
                                            <span class="visa_title"><?php echo e($plan->title); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Description")); ?>:</span>
                                            <span class="visa_short_description"><?php echo e($plan->short_description); ?></span>
                                        </li>
                                            <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Duration")); ?>:</span>
                                            <span class="visa_duration"><?php echo e($plan->heading); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Name")); ?>:</span>
                                            <span><?php echo e($data['personal_info']['first_name'].'
                                                '.$data['personal_info']['last_name']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Contact Number")); ?>:</span>
                                            <span><?php echo e($data['personal_info']['phone_number']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Email Address")); ?>:</span>
                                            <span><?php echo e($data['personal_info']['email_address']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("From")); ?>:</span>
                                            <span><?php echo e($from->name); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Live in")); ?>:</span>
                                            <span><?php echo e($live->name); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Passport No")); ?>:</span>
                                            <span><?php echo e($data['personal_info']['passport_number']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Profession")); ?>:</span>
                                            <span><?php echo e($data['personal_info']['profession']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Travel Date")); ?>:</span>
                                            <span><?php echo e($data['personal_info']['travel_date']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold"><?php echo e(__("Purpose of Travel")); ?></span>
                                            <span><?php echo e($data['personal_info']['travel_purpose']); ?></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
        
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-sm-5 mt-3">
                            <a href="<?php echo e(route('home')); ?>" class="btn btn-md btn-outline-secondary cancel-btn"><?php echo e(__("Cancel")); ?></a>
                            <button type="button" id="checkout-btn"
                                class="btn btn-md btn-primary checkout_btn"><?php echo e(__("Checkout")); ?> <i
                                    class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(function(){

        'use strict'

        $(".checkout_btn").on('click',function(){

            $.ajax({
                url : "<?php echo e(route('visa.applay.checkout')); ?>",
                method : 'get',
                success: function(response){
                    if(response.status){
                        $('.modal').modal('hide');
                        $('.modal-backdrop').hide();

                        $('.application-slide').removeClass('active');
                        $('.root_modal').html(response.html);
                        $('.root_modal .checkout-slide').addClass('active');
                    }
                }
            })
        });


        $("select[name=plan_id]").on('change', function () {

            let selectedOption = $(this).find('option:selected');
            let country = selectedOption.data('country');
            let action = selectedOption.data('action');
            let title = selectedOption.data('title');
            let description = selectedOption.data('duration');
            let duration = selectedOption.data('description');
            let currency = "<?php echo e($general->site_currency); ?>";

            if (!action) return;

            $.ajax({
                url: action,
                method: 'GET',
                success: function (response) {
                    if (response.status) {

                         iziToast.success({
                            message: response.message || 'Something went wrong.',
                            position: 'topRight'
                        });
                        $('.plan_name').text(`${response.plan_name} || ${country}`)
                        $('.plan_price').text(`${response.plan_price} ${currency}`)
                        $('.visa_title').text(`${title}`)
                        $('.visa_short_description').text(`${description}`)
                        $('.visa_duration').text(`${duration}`)
                    } else {
                        iziToast.error({
                            message: response.message || 'Something went wrong.',
                            position: 'topRight'
                        });
                    }
                },
                error: function () {
                    iziToast.error({
                            message: 'Something went wrong. Please try again.',
                            position: 'topRight'
                        });
                }
            });
        });

    })
</script><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/form_modals/wrapper/review-wrapper.blade.php ENDPATH**/ ?>