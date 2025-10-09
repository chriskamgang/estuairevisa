<?php $__env->startSection('content'); ?>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1><?php echo e(__($pageTitle)); ?></h1>
            </div>

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="card-header">
                            <div class="form-inline">
                                <label for="" class="mr-2"><?php echo e(__('Search user')); ?></label>
                                <input type="text" class="form-control" id="myInput">
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table" id="example">
                                    <thead>
                                        <tr>

                                            <th><?php echo e(__('Sl')); ?></th>
                                            <th><?php echo e(__('Full Name')); ?></th>
                                            <th><?php echo e(__('Phone')); ?></th>
                                            <th><?php echo e(__('Email')); ?></th>
                                            <th><?php echo e(__('Country')); ?></th>
                                            <th><?php echo e(__('Status')); ?></th>
                                            <th><?php echo e(__('Action')); ?></th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="filt">
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td><?php echo e($user->fullname); ?></td>

                                                <td><?php echo e($user->phone); ?></td>
                                                <td><?php echo e($user->email); ?></td>
                                                <td><?php echo e($user->address->country ?? ''); ?></td>
                                                <td>

                                                    <?php if($user->status): ?>
                                                        <span class='badge badge-success'><?php echo e(__('Active')); ?></span>
                                                    <?php else: ?>
                                                        <span class='badge badge-danger'><?php echo e(__('Inactive')); ?></span>
                                                    <?php endif; ?>

                                                </td>

                                                <td>

                                                    <a href="<?php echo e(route('admin.user.details', $user)); ?>"
                                                        class="btn btn-md btn-primary"><i class="fa fa-pen"></i></a>

                                                    <a href="<?php echo e(route('admin.login.user', $user)); ?>" target="_blank"
                                                        class="btn btn-warning btn-sm "><?php echo e(__('Login as User')); ?></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td class="text-center" colspan="100%"><?php echo e(__('No Data Found')); ?></td>
                                            </tr>
                                        <?php endif; ?>



                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php if($users->hasPages()): ?>
                            <div class="card-footer">
                                <?php echo e($users->links('backend.partial.paginate')); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script>
        $(function() {
            'use strict';

            $("#myInput").on("keyup", function() {

                var value = $(this).val().toLowerCase();

                $("#example .filt").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/backend/users/index.blade.php ENDPATH**/ ?>