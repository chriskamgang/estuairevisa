<?php
$elements = element('how_work.element');
?>
<div class="row gy-4">
    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-lg-4">
        <div class="how-work-item">
            <div class="thumb">
                <img src="<?php echo e(getFile('how_work', $element->data->image)); ?>" alt="image">
            </div>
            <div class="content">
                <span class="work-number"><?php echo e($key < 10 ? "0" : ""); ?><?php echo e($key+1); ?></span>
                        <h3 class="h4"><?php echo e(__($element->data->title)); ?></h3>
                        <p class="mb-0"><?php echo e(__($element->data->short_description)); ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH /Users/redwolf-dark/Documents/Estuaire/IMMIGRATION/Estuairevisa/core/resources/views/backend/frontend/not_editable/how_work.blade.php ENDPATH**/ ?>