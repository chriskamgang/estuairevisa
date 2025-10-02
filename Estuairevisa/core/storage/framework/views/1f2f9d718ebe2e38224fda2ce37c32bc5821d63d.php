<?php
    $blogs = \App\Models\SectionData::where('key', 'blog.element')->paginate(6);
?>
<section class="blog-section">
    <div class="container">
        <div class="row gy-4">
            <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $comment = App\Models\Comment::where('blog_id', $blog->id)->count();
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="blog-box">
                        <div class="blog-box-thumb">
                            <img src="<?php echo e(getFile('blog', $blog->data->image)); ?>" alt="image">
                        </div>
                        <div class="blog-box-content">
                            <ul class="blog-meta mb-2">
                                <li><i class="bi bi-calendar2-check"></i> <?php echo e($blog->created_at->diffforhumans()); ?>

                                </li>
                                <li><i class="bi bi-chat"></i> <?php echo e($comment); ?> <?php echo e(__('comments')); ?></li>
                            </ul>
                            <h4 class="title"><a
                                    href="<?php echo e(route('blog', [$blog->id, Str::slug($blog->data->title)])); ?>"><?php echo e($blog->data->title); ?></a>
                            </h4>
                            <a href="<?php echo e(route('blog', [$blog->id, Str::slug($blog->data->title)])); ?>" class="blog-btn">
                                <span><?php echo e(__('Read More')); ?></span>
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php echo e($blogs->links('backend.partial.paginate')); ?>

    </div>
</section>
<?php /**PATH /Users/redwolf-dark/Documents/Estuaire/IMMIGRATION/Estuairevisa/core/resources/views/backend/frontend/not_editable/all_blog.blade.php ENDPATH**/ ?>