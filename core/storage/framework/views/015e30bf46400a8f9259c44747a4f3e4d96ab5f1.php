
<div class="js-cookie-consent cookie-consent cookie-modal">

    <div class="cookies-card__icon">
        <i class="fas fa-cookie-bite"></i>
      </div>

    <p class="cookie-consent__message text-white">
        <span class="cookie-consent__message-text"><?php echo e(__($general->cookie_text)); ?></span> <a href="<?php echo e($general->cookie_link); ?>" class="site-color"><?php echo e(__("know more")); ?></a>
    </p>

    <div class="d-flex flex-wrap align-items-center gap-3">
        <button class="js-cookie-consent-agree cookie-consent__agree btn text-white">
            <?php echo e(__($general->button_text)); ?>

        </button>
        <button class="js-cookie-consent-agree cookie-consent__agree btn text-white">
            <?php echo e(__('Cancel')); ?>

        </button>
    </div>
</div>
<?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/vendor/cookieConsent/dialogContents.blade.php ENDPATH**/ ?>