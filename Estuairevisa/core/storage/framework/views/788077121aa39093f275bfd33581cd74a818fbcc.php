<?php
    $content = content('why_choose_us.content');
    $elements = element('why_choose_us.element')->take(6);
?>


<div class="row gy-5 non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false' data-gjs-editable='false'
    data-gjs-removable='false' data-gjs-propagate='["removable","editable","draggable","stylable"]'>
    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-xl-6 col-lg-12 col-md-6">
            <div class="choose-item">
                <div class="choose-item-title">
                    <h5><span><?php echo e($k + 1); ?></span> <?php echo e(__($element->data->card_title)); ?></h5>
                </div>
                <div class="choose-item-content">
                    <div class="icon">
                        <i class="<?php echo e($element->data->card_icon); ?>"></i>
                    </div>
                    <div class="choose-item-details">
                        <p class="mb-0"><?php echo e(__($element->data->card_description)); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /Users/redwolf-dark/Documents/Estuaire/IMMIGRATION/Estuairevisa/core/resources/views/backend/frontend/not_editable/why_choose_us.blade.php ENDPATH**/ ?>