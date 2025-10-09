<?php $__env->startSection('content2'); ?>
<div class="card border">
    <div class="card-header d-flex flex-wrap gap-3 justify-content-between align-items-center gap-2">
        <h5 class="mb-0 h4"><?php echo e(__("Visa Application Details")); ?></h5>
        <a href="<?php echo e(route('user.visa.all')); ?>" class="btn btn-sm btn-secondary"><?php echo e(__("Back to Applications")); ?></a>
    </div>
    <div class="card-body">
        <div class="row gy-4">
            <div class="col-md-6">
                <h6 class="text-lg"><?php echo e(__("Track Number")); ?></h6>
                <p><?php echo e($visa->order_number); ?></p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md"><?php echo e(__("Plan")); ?></h6>
                <p><?php echo e($visa->plan->title); ?></p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md"><?php echo e(__("Price")); ?></h6>
                <p><?php echo e(number_format($visa->price, 2)); ?></p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md"><?php echo e(__("Status")); ?></h6>
                <p><?= $visa->status() ?></p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md"><?php echo e(__("Note")); ?></h6>
                <p><?php echo e($visa->note ?? 'N/A'); ?></p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md"><?php echo e(__("Duration")); ?></h6>
                <p><?php echo e($visa->plan->heading ?? 'N/A'); ?></p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md"><?php echo e(__("Description")); ?></h6>
                <p><?php echo e($visa->plan->short_description ?? 'N/A'); ?></p>
            </div>
                <div class="col-md-6">
                <h6 class="text-md"><?php echo e(__("Country")); ?></h6>
                <p><?php echo e($visa->plan->country->name); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card border mt-4">
    <div class="card-header">
        <h5 class="mb-0 h4"><?php echo e(__("Personal Info")); ?></h5>
    </div>
    <div class="card-body">
        <div class="row gy-4">
            <?php $__currentLoopData = $visa->personal_info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-6">
                <h6 class="text-md"><?php echo e(ucwords(str_replace('_',' ',$name))); ?></h6>
                <p><?php echo e($value); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<div class="card border mt-4">
    <div class="card-header">
        <h5 class="mb-0 h4"><?php echo e(__("Uploaded Files")); ?></h5>
    </div>
    <div class="card-body">
        <div class="row gy-4">
            <?php $__empty_1 = true; $__currentLoopData = $visa->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php

                    $extension = explode(".",$file)[1];
                    $type = in_array(strtolower($extension),['jpg', 'jpeg', 'png', 'gif', 'webp']) ? "image" : "pdf";
                    
                ?>
            
                <div class="col-md-3">
                    <div class="card">
                        <?php if($type == "image"): ?>
                            <img src="<?php echo e(getFile('visa_document', $file)); ?>" class="card-img-top" alt="Uploaded image">
                        <?php else: ?>
                            <?php 
                                $path = asset(filePath('visa_document')."/".$file);
                            ?>
                            <div class="p-3 text-center">
                                <a href="<?php echo e($path); ?>" target="_blank">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-2"></i>
                                    <div><?php echo e(__("Download File")); ?></div>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted"><?php echo e(__("No files uploaded.")); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="card border mt-4">
    <div class="card-header">
        <h5 class="mb-0 h4"><?php echo e(__("Additional Info")); ?></h5>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong><?php echo e(__("Created At")); ?>:</strong> <?php echo e($visa->created_at->format('Y-m-d')); ?></li>
            <li class="list-group-item"><strong><?php echo e(__("Updated At")); ?>:</strong> <?php echo e($visa->updated_at->format('Y-m-d')); ?></li>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layout.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/user/visa/details.blade.php ENDPATH**/ ?>