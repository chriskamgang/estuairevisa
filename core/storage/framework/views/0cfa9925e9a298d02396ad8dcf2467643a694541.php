<?php $__env->startSection('content'); ?>
<div class="main-content">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4><?php echo e(__('Apply List')); ?></h4>
            <form action="">
                <div class="input-group">
                    <input type="text" class="form-control" name="order_number"
                        value="<?php echo e(request()->order_number ?? ''); ?>" placeholder="<?php echo e(__('Track Number')); ?>" >
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2"> <?php echo e(__('Search')); ?></button>
                </div>
            </form>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?php echo e(__("User")); ?></th>
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
                            <div class="d-flex">
                                <div class="pr-3">
                                    <img src="<?php echo e(getFile('user',$item->checkout->user->image)); ?>" class="avatar-image">
                                </div>
                                <div>
                                    <a href="<?php echo e(route('admin.user.details',$item->checkout->user_id)); ?>"><?php echo e($item->checkout->user->fullname); ?>

                                        </br><?php echo e($item->checkout->user->email); ?></a>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($item->order_number); ?></td>
                        <td><?php echo e($item->plan->title); ?></td>
                        <td><?php echo e(number_format($item->price,2)); ?></td>
                        <td>
                            <?= $item->status() ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.visa.details',$item->order_number)); ?>"
                                class="btn btn-sm btn-primary mr-1">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button data-action="<?php echo e(route('admin.visa.change.status',$item->order_number)); ?>"
                                data-status="<?php echo e($item->status); ?>" class="btn btn-sm change_status_btn btn-info mr-1">
                                <i class="fa fa-bell"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center"><?php echo e(__('No Field available.')); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <?php echo e($items->links()); ?>

        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="changeStatus" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="" method="post">
            <?php echo csrf_field(); ?>


            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('Change Status')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for=""><?php echo e(__("Status")); ?></label>
                        <select name="status" class="form-control">
                            <option value="pending"><?php echo e(__("Pending")); ?></option>
                            <option value="issues"><?php echo e(__("Issues")); ?></option>
                            <option value="under_review"><?php echo e(__("Under Review")); ?></option>
                            <option value="proccessing"><?php echo e(__("Proccessing")); ?></option>
                            <option value="complete"><?php echo e(__("Complete")); ?></option>
                            <option value="shipped"><?php echo e(__("Shipped")); ?></option>
                            <option value="reject"><?php echo e(__("Rejected")); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for=""><?php echo e(__("Note")); ?></label>
                        <textarea name="note" class="form-control"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>

                    <button type="submit" class="btn btn-danger"><?php echo e(__('Submit')); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>


<?php $__env->stopSection(); ?>





<?php $__env->startPush('script'); ?>
<script>
    $(function() {
            'use strict';

            $(".change_status_btn").on('click',function(){

                let modal = $("#changeStatus");
                    modal.find('form').attr('action',$(this).data('action'));
                    modal.find("select[name='status']").val($(this).data('status'));
                    modal.modal('show');

            });


        });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('backend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/backend/visa/list.blade.php ENDPATH**/ ?>