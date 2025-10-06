<?php $__env->startSection('frontend_content'); ?>
    <?php
        $breadcrumb = content('breadcrumb.content');
    ?>

    <?php if($page->is_breadcrumb == 1): ?>
        <section class="breadcrumbs"
            style="background-image: url(<?php echo e(getFile('breadcrumb', @$breadcrumb->data->backgroundimage)); ?>);">
            <div class="container">
                <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center text-capitalize">
                    <h2><?php echo e(__($pageTitle ?? '')); ?></h2>
                    <ol>
                        <li><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
                        <li><?php echo e(__($pageTitle ?? '')); ?></li>
                    </ol>
                </div>
            </div>
        </section>
    <?php endif; ?>


 <div class="pagebuilder-content">
        <?php echo convertHtml($page->html); ?>

    </div>
<?php $__env->stopSection(); ?>

 <?php $__env->startPush('style'); ?>
<style>
    <?php echo replaceBaseUrl($page->css); ?>

</style>
<?php $__env->stopPush(); ?> 



<?php echo $__env->make('frontend.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/home.blade.php ENDPATH**/ ?>