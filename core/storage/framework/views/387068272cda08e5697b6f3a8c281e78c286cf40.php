<?php
    $content = content('faq.content');
    $elements = element('faq.element');
?>

<div class="accordion" id="accordionExample">
    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="accordion-item">
            <h4 class="accordion-header" id="heading-<?php echo e($loop->iteration); ?>">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                    data-bs-target="#collapse<?php echo e($loop->iteration); ?>" aria-expanded="false">
                    <?php echo e(__($item->data->question)); ?>

                </button>
            </h4>
            <div id="collapse<?php echo e($loop->iteration); ?>" class="accordion-collapse collapse"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <p> <?php echo e(__($item->data->answer)); ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /Users/redwolf-dark/Documents/Estuaire/IMMIGRATION/Estuairevisa/core/resources/views/backend/frontend/not_editable/faq.blade.php ENDPATH**/ ?>