<?php
$content = content('contact.content');
$elements = element('footer.element');
?>

<header class="header-section">
  <div class="header-top-part">
    <div class="container">
      <div class="header-info-part">
        <ul class="header-info-list">
            <li>
                <i class="bi bi-geo-alt-fill"></i>
                <span><?php echo e(@$content->data->location); ?></span>
            </li>
            <li>
                <i class="bi bi-envelope-fill"></i>
                <a href="mailto:<?php echo e(@$content->data->email); ?>"><?php echo e(@$content->data->email); ?></a>
            </li>
        </ul>
        <div class="header-top-right">
            <ul class="header-social-links d-flex align-items-center">
                <?php $__currentLoopData = @$elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><a href="<?php echo e($element->data->social_link); ?>" target="_blank" aria-label="Social media link"><i class="<?php echo e($element->data->social_icon); ?>"></i></a></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <div class="dropdown">
                <button class="lang-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-globe"></i>
                    <span class="ms-1"><?php echo e(__(ucwords(selectedLanguage()->name))); ?></span>
                </button>

                <ul class="dropdown-menu">
                    <?php $__currentLoopData = $language_top; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $top): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a class="dropdown-item <?php echo e(languageSelection($top->short_code, 'active')); ?>"
                            href="<?php echo e(route('changeLang', $top->short_code)); ?>"><?php echo e(__(ucwords($top->name))); ?></a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div class="header-main-part-wrapper">
    <div class="container">
      <div class="header-main-part">
        <a href="<?php echo e(route('home')); ?>" class="logo-part">
            <img src="<?php echo e(getFile('logo', $general->logo)); ?>" alt="logo">
        </a>
        <div class="header-menu-list-wrapper">
            <button type="button" class="mobile-menu-close-btn"><i class="bi bi-x-lg"></i></button>
            <ul class="header-menu-list">
                <?php $__currentLoopData = getMenus("headers"); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><a class="nav-link" href="<?php echo e(route('pages', $menu->page->slug)); ?>">
                    <?php echo e(__($menu->page->name)); ?></a>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if(session('checkout_data')): ?>
                <?php
                    $total_cart_item = count(session('checkout_data')['items'])
                ?>

                <li>
                    <a class="nav-link" href="<?php echo e(route('visa.cart')); ?>"><?php echo e(__("Cart")); ?> <span class="badge badge-info"><?php echo e($total_cart_item); ?></span></a>
                </li>
                <?php endif; ?>
            </ul>
            <div class="d-lg-none px-3 mt-2">
                <?php if(auth()->guard()->check()): ?> 
                     <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-md btn-primary d-inline-flex align-items-center gap-2">
                      <i class="bi bi-person-circle"></i>
                      <?php echo e(__("Dashboard")); ?>

                    </a>
                <?php else: ?> 
                    <a href="<?php echo e(route('user.login')); ?>" class="btn btn-md btn-primary d-inline-flex align-items-center gap-2">
                      <i class="bi bi-person-circle"></i>
                      <?php echo e(__("Login")); ?>

                    </a>
                <?php endif; ?>
            </div>
        </div>
        <button type="button" class="mobile-menu-open-btn ms-auto me-2"><i class="bi bi-list"></i></button>

        
        <div class="d-lg-block d-none">
            <?php if(auth()->guard()->check()): ?> 
                 <a href="<?php echo e(route('user.dashboard')); ?>" class="btn btn-md btn-primary d-inline-flex align-items-center gap-2">
                  <i class="bi bi-person-circle"></i>
                  <?php echo e(__("Dashboard")); ?>

                </a>
            <?php else: ?> 
                <a href="<?php echo e(route('user.login')); ?>" class="btn btn-md btn-primary d-inline-flex align-items-center gap-2">
                  <i class="bi bi-person-circle"></i>
                  <?php echo e(__("Login")); ?>

                </a>
            <?php endif; ?>
        </div>
        
      </div>
    </div>
  </div>
</header>

<?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/layout/header.blade.php ENDPATH**/ ?>