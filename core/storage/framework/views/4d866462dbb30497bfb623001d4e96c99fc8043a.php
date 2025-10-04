<?php
$content = content('contact.content');
$contentlink = content('footer.content');
$footersociallink = element('footer.element');

?>
<footer class="footer-section">
  <div class="footer-top">
    <div class="container">
      <div class="subscribe-wrapper">
        <div class="row gy-4 justify-content-between align-items-center">
          <div class="col-xxl-4 col-xl-5 col-lg-6">
            <h2 class="title"><?php echo e(__('Subscribe Our Newsletter')); ?></h2>
            <p class="mb-0"><?php echo e(__('Join our newsletter to keep up to date with us!')); ?></p>
          </div>
          <div class="col-xxl-6 col-xl-7 col-lg-6">
            <form class="subscribe-form">
              <input type="email" name="subscriber_email" required placeholder="Enter your email address">
              <i class="bi bi-envelope"></i>
              <button type="submit" class="subscribe-btn"><?php echo e(__('Subscribe')); ?></button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="footer-middle">
    <div class="container">
      <div class="row gy-4 justify-content-between">
        <div class="col-lg-4 col-md-9">
          <a href="<?php echo e(route('home')); ?>">
            <img src="<?php echo e(getFile('logo', $general->logo)); ?>" class="nir-footer-logo" alt="logo">
          </a>
          <p class="mt-4 mb-0"><?php echo e(__(@$contentlink->data->footer_short_description)); ?></p>
          <ul class="d-flex flex-wrap align-items-center gap-3 mt-3">
            <?php $__empty_1 = true; $__currentLoopData = @$footersociallink; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li>
              <a href="<?php echo e(__($item->data->social_link)); ?>" target="_blank" aria-label="Social media link"><i
                  class="<?php echo e($item->data->social_icon); ?>"></i></a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php endif; ?>
          </ul>
        </div>
        <div class="col-lg-2 col-sm-4">
          <div class="nir-footer-item">
            <h6 class="nir-footer-item-title"><?php echo e(__("Quick Link")); ?></h6>
            <ul class="nir-footer-list">
              <?php $__currentLoopData = getMenus('quick_link'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><a href="<?php echo e(route('pages', $menu->page->slug)); ?>"><?php echo e(__($menu->page->name)); ?></a>
              </li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
        </div>
        <div class="col-lg-2 col-sm-4">
          <div class="nir-footer-item">
            <h6 class="nir-footer-item-title"><?php echo e(__("Company Info")); ?></h6>
            <ul class="nir-footer-list">
              <?php $__currentLoopData = getMenus('company'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><a href="<?php echo e(route('pages', $menu->page->slug)); ?>"><?php echo e(__($menu->page->name)); ?></a></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-sm-4">
          <div class="nir-footer-item">
            <h6 class="nir-footer-item-title"><?php echo e(__("GET IN TOUCH")); ?></h6>
            <ul class="nir-footer-list">
              <li>
                <span class="caption"><i class="bi bi-telephone-fill"></i></span>
                <span class="description">
                  <a href="tel:<?php echo e(@$content->data->phone); ?>">+<?php echo e(__(@$content->data->phone)); ?></a>
                </span>
              </li>
              <li class="mt-3">
                <span class="caption"><i class="bi bi-envelope-fill"></i></span>
                <span class="description">
                  <a href="mailto:<?php echo e(@$content->data->email); ?>"><?php echo e(__(@$content->data->email)); ?></a>
                </span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if($general->copyright): ?>
  <div class="footer-bottom">
    <div class="container">
      <p class="mb-0 text-center"><?php echo e(__($general->copyright)); ?></p>
    </div>
  </div>
  <?php endif; ?>
</footer><?php /**PATH /Users/redwolf-dark/Documents/Estuaire/IMMIGRATION/Estuairevisa/core/resources/views/frontend/layout/footer.blade.php ENDPATH**/ ?>