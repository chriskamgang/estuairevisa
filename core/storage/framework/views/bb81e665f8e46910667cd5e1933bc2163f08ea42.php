
<?php if(Session::has('error')): ?>
<script>
    $(function() {
        "use strict";
        iziToast.error({
            message: "<?php echo e(session('error')); ?>",
            position: 'topRight'
        });
    });
</script>
<?php endif; ?>

<?php if(Session::has('success')): ?>
<script>
    $(function() {
        "use strict";
        iziToast.success({
            message: "<?php echo e(session('success')); ?>",
            position: 'topRight'
        });
    });
</script>
<?php endif; ?>

<?php if(session()->has('notify')): ?>
<?php $__currentLoopData = session('notify'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <script>
        $(function() {
            "use strict";
            iziToast.<?php echo e($msg[0]); ?>({
                message: "<?php echo e(trans($msg[1])); ?>",
                position: "topRight"
            });
        });
    </script>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php if($errors->any()): ?>
<script>
    $(function() {
        "use strict";
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            iziToast.error({
                message: "<?php echo e(__($error)); ?>",
                position: "topRight"
            });
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });
</script>
<?php endif; ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/notify.blade.php ENDPATH**/ ?>