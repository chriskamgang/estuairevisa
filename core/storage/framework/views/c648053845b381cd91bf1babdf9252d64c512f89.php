<?php $__env->startSection('content'); ?>

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1><?php echo e(__($pageTitle)); ?></h1>
            </div>
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="form-group col-md-3 mb-3">
                                        <label class="col-form-label"><?php echo e(__('MTN Mobile Money Image')); ?></label>

                                        <div id="image-preview" class="image-preview"
                                            style="background-image:url(<?php echo e(getFile('gateways', $gateway->gateway_image ?? '')); ?>);">
                                            <label for="image-upload"
                                                id="image-label"><?php echo e(__('Choose File')); ?></label>
                                            <input type="file" name="gateway_image" id="image-upload" />
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for=""><?php echo e(__('Gateway Currency')); ?></label>
                                                <input type="text" name="gateway_currency"
                                                    class="form-control form_control site-currency"
                                                    value="<?php echo e($gateway->gateway_parameters->gateway_currency ?? 'FCFA'); ?>">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label><?php echo e(__('Conversion Rate')); ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <?php echo e('1 ' . $general->site_currency . ' = '); ?>

                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control currency" name="rate"
                                                        value="<?php echo e(number_format($gateway->rate ?? 1, 4)); ?>">

                                                    <div class="input-group-append">
                                                        <div class="input-group-text append_currency">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for=""><?php echo e(__('Freemopay App Key')); ?></label>
                                                <input type="text" name="app_key" class="form-control"
                                                    value="<?php echo e($gateway->gateway_parameters->app_key ?? ''); ?>">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for=""><?php echo e(__('Freemopay Secret Key')); ?></label>
                                                <input type="text" name="secret_key" class="form-control"
                                                    value="<?php echo e($gateway->gateway_parameters->secret_key ?? ''); ?>">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for=""><?php echo e(__('Charge')); ?></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="charge"
                                                        value="<?php echo e(number_format($gateway->charge ?? 0, 2)); ?>">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <?php echo e($general->site_currency); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for=""><?php echo e(__('Allow as payment method')); ?></label>
                                                <select name="status" id="" class="form-control selectric">
                                                    <option value="1" <?php echo e($gateway->status ? 'selected' : ''); ?>>
                                                        <?php echo e(__('Yes')); ?>

                                                    </option>
                                                    <option value="0" <?php echo e($gateway->status ? '' : 'selected'); ?>>
                                                        <?php echo e(__('No')); ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <h6><i class="fas fa-info-circle"></i> <?php echo e(__('Instructions')); ?></h6>
                                            <ul class="mb-0">
                                                <li><?php echo e(__('Obtenez vos clés API depuis votre compte Freemopay')); ?></li>
                                                <li><?php echo e(__('URL de callback: ')); ?> <code><?php echo e(route('freemopay.callback')); ?></code></li>
                                                <li><?php echo e(__('Les paiements MTN Mobile Money seront traités automatiquement')); ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit"
                                        class="btn btn-primary float-right"><?php echo e(__('Update MTN Mobile Money Information')); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        "use strict"
        $(function() {
            $('.site-currency').on('keyup', function() {
                var currrency = $(this).val()
                $('.append_currency').text(currrency)
            })

            $('.site-currency').keyup();
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/backend/gateways/mtnmoney.blade.php ENDPATH**/ ?>