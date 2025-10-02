<?php
$elements = element('airline.element');
$chunks = $elements->split(2);
?>


<div class="airline-slider-one">
  <?php $__currentLoopData = $chunks[0] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="single-slide">
    <div class="airline-item">
      <img src="<?php echo e(getFile('airline',$item->data->image)); ?>" alt="image">
    </div>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
<div class="airline-slider-two">

  <?php $__currentLoopData = $chunks[1] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <div class="single-slide">
    <div class="airline-item">
      <img src="<?php echo e(getFile('airline',$item->data->image)); ?>" alt="image">
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

    initSlickSlider($('.airline-slider-one'), {
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 5000,
        cssEase: 'linear',
        accessibility: true,
        responsive: [
            {
                breakpoint: 1400,
                settings: { slidesToShow: 4 }
            },
            {
                breakpoint: 480,
                settings: { slidesToShow: 2 }
            }
        ]
    });

    initSlickSlider($('.airline-slider-two'), {
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 5000,
        cssEase: 'linear',
        accessibility: true,
        responsive: [
            {
                breakpoint: 1400,
                settings: { slidesToShow: 4 }
            },
            {
                breakpoint: 480,
                settings: { slidesToShow: 2 }
            }
        ]
    });
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH /Users/redwolf-dark/Documents/Estuaire/IMMIGRATION/Estuairevisa/core/resources/views/backend/frontend/not_editable/airline.blade.php ENDPATH**/ ?>