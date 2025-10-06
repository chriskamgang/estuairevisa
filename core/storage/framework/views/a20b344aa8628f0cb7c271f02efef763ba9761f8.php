<?php
$content = content('testimonial.content');
$elements = element('testimonial.element');
?>


<div class="testimonial-slider">
    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="testimonial-slide">
        <div class="testimonial-item">
            <span class="icon"><i class="bi bi-quote"></i></span>
            <p class="testimonial-details"><?php echo e(__($element->data->answer)); ?></p>

            <div class="client">
                <img src="<?php echo e(getFile('testimonial', $element->data->image)); ?>" alt="image" class="thumb">
                <div class="content">
                    <h3 class="name"><?php echo e($element->data->client_name); ?></h3>
                    <p class="mb-0 ratings">
                        <?php
                        $notfill = 5 - $element->data->review;
                        ?>
                        <?php for($i = 1; $i <= $element->data->review; $i++): ?>
                            <i class="bi bi-star-fill"></i>
                            <?php endfor; ?>
                            <?php for($i = 1; $i <= $notfill; $i++): ?> <i class="bi bi-star"></i>
                                <?php endfor; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php $__env->startPush('script'); ?>
<script>
    $(function () {
    function initSlickSlider($selector, options) {
        if ($selector.hasClass('slick-initialized')) {
            $selector.slick('unslick');
        }
        $selector.slick(options);
    }

    initSlickSlider($('.testimonial-slider'), {
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: `<span class="testi-prev"><i class="bi bi-arrow-left"></i></span>`,
        nextArrow: `<span class="testi-next"><i class="bi bi-arrow-right"></i></span>`,
        dots: false,
        autoplay: true,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                    dots: true,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                    dots: true,
                }
            }
        ]
    });
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/backend/frontend/not_editable/testimonial.blade.php ENDPATH**/ ?>