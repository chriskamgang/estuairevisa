<li>
    <a href="#">
        <img src="<?php echo e(getFile('user', $user->image)); ?>" class="ref-img" alt="">
        <p class="mb-0"><?php echo e($user->full_name); ?> <span class="font-weight-bolder">
            <?php if($level > 0): ?>
            ( <?php echo e($level); ?> )
            <?php endif; ?>
            </span></p>
    </a>
    <?php if(!empty($user->children) && $user->children->count() > 0): ?>
        <ul>
            <?php $__currentLoopData = $user->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('frontend.user..reference_tree', ['user' => $child, 'level' => $level + 1], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php endif; ?>
</li>
<?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/user//reference_tree.blade.php ENDPATH**/ ?>