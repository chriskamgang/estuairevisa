<?php 
    $elements = element('featured.element');
?>

<div class="row row-cols-lg-5 row-cols-md-3 row-cols-2  rowcols gy-4 justify-content-center">
    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="col">
        <div class="featured-on-item text-center">
          <img src="<?php echo e(getFile('featured', $element->data->image)); ?>" alt="image">
          <h3 class="title"><?php echo e(__($element->data->title)); ?></h3>
        </div>
      </div>
     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/backend/frontend/not_editable/featured.blade.php ENDPATH**/ ?>