<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name='description' content="<?php echo e($general->seo_description); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link rel="shortcut icon" type="image/png" href="<?php echo e(getFile('icon', $general->favicon)); ?>">

    <title>
        <?php if($general->sitename): ?>
        <?php echo e(__($general->sitename)); ?>

        <?php endif; ?>

    </title>

    <link rel="stylesheet" href="<?php echo e(asset('asset/frontend/css/cookie.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('asset/frontend/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('asset/frontend/css/bootstrap-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('asset/frontend/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('asset/frontend/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('asset/frontend/css/slick.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('asset/frontend/css/izitoast.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('asset/frontend/css/style.css')); ?>">



    <?php echo $__env->yieldPushContent('style'); ?>

    <link rel="stylesheet"
        href="<?php echo e(asset('asset/frontend/css/color.php?primary_color=' . str_replace('#', '', $general->primary_color))); ?>">
</head>


<body>

    <?php if($general->preloader_status): ?>
    <div class="rt-preloader">
      <div class="rt-cube-grid">
        <div class="rt-cube rt-cube1"></div>
        <div class="rt-cube rt-cube2"></div>
        <div class="rt-cube rt-cube3"></div>
        <div class="rt-cube rt-cube4"></div>
        <div class="rt-cube rt-cube5"></div>
        <div class="rt-cube rt-cube6"></div>
        <div class="rt-cube rt-cube7"></div>
        <div class="rt-cube rt-cube8"></div>
        <div class="rt-cube rt-cube9"></div>
      </div>
    </div>
    <?php endif; ?>


    <?php if($general->allow_modal): ?>
    <?php echo $__env->make('cookieConsent::index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>


    <?php if($general->analytics_status): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e($general->analytics_key); ?>"></script>
    <script>
        'use strict'
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag("js", new Date());
            gtag("config", "<?php echo e($general->analytics_key); ?>");
    </script>
    <?php endif; ?>



    <?php echo $__env->yieldContent('content'); ?>


    <button type="button" class="top-action-btn">
      <span class="icon">
        <i class="bi bi-arrow-up"></i>
      </span>
      <span class="caption">Top</span>
    </button>


    <script src="<?php echo e(asset('asset/frontend/js/jquery-3.7.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/aos.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/iconify-icon.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/intlTelInput.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/magnifc-popup.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/slick.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/izitoast.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/jquery.basictable.min.js')); ?>"></script>
    <script src="<?php echo e(asset('asset/frontend/js/app.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('script'); ?>

    <?php echo $__env->make('frontend.notify', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <script>
    $(function(){

            'use strict';
            $(".changeLang").on("change", function() {
                let url = $(this).find('option:selected').data('action');
                window.location.href = url;
            });
            
             $(document).on('click', '.subscribe-btn', function(e) {
                'use strict'
                    e.preventDefault();
                    const email = $("input[name=subscriber_email]").val();

                    if(!email){
                       iziToast.error({
                                message: "Email is required",
                                position: 'topRight',
                            });
                      return false;
                    }
                    var url = "<?php echo e(route('subscribe')); ?>";
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    

        
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            email: email,
                        },
                        success: (data) => {
                            iziToast.success({
                                message: data.message,
                                position: 'topRight',
                            });
                            $('input[name=subscriber_email]').val('');
        
                        },
                        error: (error) => {
                            if (typeof(error.responseJSON.errors.email) !== "undefined") {
                                iziToast.error({
                                    message: error.responseJSON.errors.email,
                                    position: 'topRight',
                                });
                            }
        
                        }
                })

            $(".changeLang").on("change", function() {
                let url = $(this).find('option:selected').data('action');
                window.location.href = url;
            });


              $(window).on("scroll", function () {
                if ($(this).scrollTop() > 200) {
                  $(".top-action-btn").fadeIn(200);
                } else {
                  $(".top-action-btn").fadeOut(200);
                }
              });
        });
        
        document.addEventListener("DOMContentLoaded", function () {
          const preloader = document.querySelector(".rt-preloader");
          if (preloader) {
            preloader.style.display = "none";
          }
        });
        
        


           
        })
  </script>
</body>
</html><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/layout/app.blade.php ENDPATH**/ ?>