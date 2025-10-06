<?php
$countries = \App\Models\Country::active()->slider()->get();
?>

<div class="location-slider non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
    data-gjs-editable='false' data-gjs-removable='false'
    data-gjs-propagate='["removable","editable","draggable","stylable"]'>
    <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="single-slide">
        <div class="location-item">
            <img src="<?php echo e(getFile('country',$country->image)); ?>" alt="image">
            <h3 class="title h6"><?php echo e($country->name); ?></h3>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->startPush('script'); ?>
<script>
    $(function(){
            'use strict'

            function initSlickSlider($selector, options) {

                if ($selector.hasClass('slick-initialized')) {
                    $selector.slick('unslick');
                }
                $selector.slick(options);
            }


            initSlickSlider($('.location-slider'), {
                infinite: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                arrows: false,
                dots: false,
                autoplay: true,
                autoplaySpeed: 0,
                speed: 5000,
                cssEase: 'linear',
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,
                        }
                    }
                ]
            });
        })
</script>
<?php $__env->stopPush(); ?><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/backend/frontend/not_editable/destination.blade.php ENDPATH**/ ?>