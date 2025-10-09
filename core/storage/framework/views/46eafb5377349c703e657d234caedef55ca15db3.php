<?php $__env->startSection('content'); ?>


<?php
    $breadcrumb = content('breadcrumb.content');
?>

<?php echo $__env->make('frontend.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<section class="breadcrumbs"
    style="background-image: url(<?php echo e(getFile('breadcrumb', @$breadcrumb->data->backgroundimage)); ?>);">
    <div class="container">
        <div class="d-flex flex-wrap gap-3s justify-content-between align-items-center text-capitalize">
            <h2 class="mb-0"><?php echo e(__($pageTitle ?? '')); ?></h2>
            <ol>
                <li><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
                <li><?php echo e(__($pageTitle ?? '')); ?></li>
            </ol>
        </div>
    </div>
</section>


<div class="nir-user-dashboard">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-3">
                <div class="nir-user-sidebar h-100">
                    <div class="d-flex justify-content-center">
                        <img src="<?php echo e(getFile('user',auth()->user()->image)); ?>" alt="image"
                            class="avatar-6xl rounded-circle object-fit-cover">
                    </div>
                    <div class="text-center mt-3">
                        <h5 class="mb-0"><?php echo e(auth()->user()->fullname); ?></h5>
                        <p class="mb-0"><?php echo e(auth()->user()->username); ?></p>
                    </div>
    
                    <hr>
    
                    <?php echo $__env->make('frontend.layout.user_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
            <div class="col-lg-9 ps-xxl-4">
                <?php echo $__env->yieldContent('content2'); ?>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('frontend.layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/layout/master2.blade.php ENDPATH**/ ?>