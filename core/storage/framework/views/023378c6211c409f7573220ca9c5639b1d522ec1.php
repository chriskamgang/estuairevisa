<?php $__env->startSection('content2'); ?>
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
                <div class="tab-btn-group">
                    <a href="<?php echo e(route('user.ticket.index')); ?>"
                        class="tab-btn <?php echo e(request()->status == '' ? 'active' : ''); ?>"><i class="fa fa-inbox fa-lg"
                            aria-hidden="true"></i>
                        <?php echo e(__('All Ticket')); ?> <span class="num"><?php echo e($tickets_all); ?></span>
                    </a>
                    <a href="<?php echo e(route('user.ticket.status', 'answered')); ?>"
                        class="tab-btn <?php echo e(request()->status == 'answered' ? 'active' : ''); ?>"><?php echo e(__('Answered')); ?>

                        <span class="num"><?php echo e($tickets_answered); ?></span>
                    </a>
                    <a href="<?php echo e(route('user.ticket.status', 'pending')); ?>"
                        class="tab-btn <?php echo e(request()->status == 'pending' ? 'active' : ''); ?>">
                        <?php echo e(__('Pending')); ?> <span class="num"><?php echo e($tickets_pending); ?></span>
                    </a>
                </div>
                <button type="button" id="new-ticket" class="btn btn-primary btn-sm"><?php echo e(__('Create Ticket')); ?> <i class="fa fa-envelope"></i></button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table responsive-table head-light-bg nir-table">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Ticket ID')); ?></th>
                            <th><?php echo e(__('Subject')); ?></th>
                            <th><?php echo e(__('Total Reply')); ?></th>
                            <th><?php echo e(__('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($ticket->support_id); ?></td>
                                <td><?php echo e($ticket->subject); ?></td>

                                <td>(<?php echo e($ticket->ticketReplies->count()); ?>)
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <button data-route="<?php echo e(route('user.ticket.update', $ticket->id)); ?>"
                                            data-ticket="<?php echo e($ticket); ?>" data-message="<?php echo e($ticket->ticketReplies()->where('ticket_id',$ticket->id)->first()); ?>" class="avatar-md text-white d-inline-flex justify-content-center align-items-center rounded-2 bg-success edit-modal">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <a href="<?php echo e(route('user.ticket.show', $ticket->id)); ?>"
                                            class="avatar-md text-white d-inline-flex justify-content-center align-items-center rounded-2 bg-info"><i class="fas fa-eye"></i></a>

                                        <a data-route="<?php echo e(route('user.ticket.destroy', $ticket->id)); ?>"
                                            class="avatar-md text-white d-inline-flex justify-content-center align-items-center rounded-2 bg-danger delete"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td data-caption="<?php echo e(__('Status')); ?>" class="text-center" colspan="100%">
                                    <?php echo e(__('No Data Found')); ?>

                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade " id="add" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form action="<?php echo e(route('user.ticket.store')); ?>" enctype="multipart/form-data" method="post">
                <?php echo csrf_field(); ?>
                <div class="modal-content bg-body">
                    <div class="modal-header align-items-center justify-content-between">
                        <h5 class="modal-title"><?php echo e(__('Create Ticket')); ?></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo e(__('Subject')); ?></label>
                                    <input type="text" name="subject" class="form-control" required=""
                                        placeholder="<?php echo e(__('subject here')); ?>" value="<?php echo e(old('subject')); ?>">
                                </div>

                            </div>
                            <div class="row align-items-center mt-2">
                                <div class="col-lg-12">
                                    <div class="form-group ticket-comment-box">
                                        <label for="exampleFormControlTextarea1"><?php echo e(__('Message')); ?></label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" cols="30" name="message"
                                            placeholder="Massage"><?php echo e(old('message')); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <div id="image-preview" class="image-preview">
                                        <label for="image-upload" id="image-label"
                                            class="text-white"><?php echo e(__('Choose File')); ?></label>
                                        <input type="file" name="file" id="image-upload" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger"
                                data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                            <button type="submit" class="btn btn-primary"><?php echo e('Create Ticket'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form action="" enctype="multipart/form-data" method="post">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-content bg-body">
                    <div class="modal-header align-items-center justify-content-between">
                        <h5 class="modal-title"><?php echo e(__('Edit Ticket')); ?></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo e(__('Subject')); ?></label>
                                    <input type="text" name="subject" class="form-control" required=""
                                        placeholder="<?php echo e(__('subject here')); ?>" value="<?php echo e(old('subject')); ?>">
                                </div>

                            </div>
                            <div class="row align-items-center mt-2">
                                <div class="col-lg-12">
                                    <div class="form-group ticket-comment-box">
                                        <label for="exampleFormControlTextarea1"><?php echo e(__('Message')); ?></label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" cols="30" name="message"
                                            placeholder="Massage"><?php echo e(old('message')); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <div id="image-preview" class="image-preview">
                                        <label for="image-upload" id="image-label"
                                            class="text-white"><?php echo e(__('Choose File')); ?></label>
                                        <input type="file" name="file" id="image-upload" class="form-control" />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger"
                                data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                            <button type="submit" class="btn btn-primary"><?php echo e('Update Ticket'); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" tabindex="-3" id="delete" role="dialog">
        <div class="modal-dialog" role="document">
            <form action="" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>

                <div class="modal-content">
                    <div class="modal-header align-items-center justify-content-between">
                        <h5 class="modal-title"><?php echo e(__('Delete Support Ticket')); ?></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo e(__('Are you sure to delete this ticket')); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger"
                            data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                        <button type="submit" class="btn btn-primary"><?php echo e(__('Delete')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $__env->stopSection(); ?>


    <?php $__env->startPush('script'); ?>
        <script>
            $(function() {
                'use strict'

                $('#new-ticket').on('click', function() {

                    const modal = $('#add');

                    modal.modal('show');

                })

                $('.edit-modal').on('click', function(e) {

                    e.preventDefault();

                    const modal = $('#edit_modal');

                    modal.find('form').attr('action', $(this).data('route'));

                    modal.find('input[name=subject]').val($(this).data('ticket').subject)
                    modal.find('textarea[name=message]').val($(this).data('message').message)

                    modal.modal('show');
                })

                $('.delete').on('click', function(e) {

                    e.preventDefault();

                    const modal = $('#delete');

                    modal.find('form').attr('action', $(this).data('route'));

                    modal.modal('show');

                })
            })
        </script>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/user/ticket/list.blade.php ENDPATH**/ ?>