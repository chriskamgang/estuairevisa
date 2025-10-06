<?php $__env->startSection('content'); ?>

 
  <?php echo $__env->make('frontend.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  
  <div class="root_modal"></div>

  <?php echo $__env->yieldContent('frontend_content'); ?>
  
  <?php echo $__env->make('frontend.layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/layout/master.blade.php ENDPATH**/ ?>