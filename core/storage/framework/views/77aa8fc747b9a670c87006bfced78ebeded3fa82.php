<nav class="navbar navbar-expand-lg main-navbar">

    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li class="bars-icon-navbar"><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg "><i
                        class="fas fa-bars"></i></a></li>
            </li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li>
            <div class="search-element">
                <a href="<?php echo e(route('home')); ?>" target="_blank"
                    class="bg-primary text-white text-decoration-none p-2 rounded">
                    <i class="fas fa-home  mr-2"></i>
                    <span class="font-weight-bold"><?php echo e(__('Visit Frontend')); ?></span>
                </a>
            </div>
        </li>
        <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                class="nav-link notification-toggle nav-link-lg pt-8px">
                <span class="noti-icon">
                    <iconify-icon icon="iconamoon:ticket-light"></iconify-icon>
                </span>
                <span class="badge badge-danger notification-badge"><?php echo e($pendingTicketNotifications->count()); ?></span> </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
              
                <div class="dropdown-header"><?php echo e(__('Ticket Notifications')); ?>

                    <div class="float-right">
                        <a href="<?php echo e(route('admin.markNotification','ticket')); ?>"><?php echo e(__('Mark All As Read')); ?></a>
                    </div>
                </div>
                
                
                <div class="dropdown-list-content dropdown-list-icons">
                    <?php $__empty_1 = true; $__currentLoopData = $pendingTicketNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e($notification->data['url']); ?>" class="dropdown-item dropdown-item-unread">
                        <div class="dropdown-item-icon bg-primary text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            <?php echo e($notification->data['message']); ?>

                            <div class="time text-primary"><?php echo e($notification->created_at->diffforhumans()); ?></div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-center"><?php echo e(__('There are no new notifications')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </li>

        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg pt-8px">
                <span class="noti-icon">
                    <iconify-icon icon="uit:wallet"></iconify-icon>
                </span>
                <span class="badge badge-danger notification-badge"><?php echo e($pendingPaymentNotification->count()); ?></span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header"><?php echo e(__('Payment Notifications')); ?>

                    <div class="float-right">
                        <a href="<?php echo e(route('admin.markNotification','payment')); ?>"><?php echo e(__('Mark All As Read')); ?></a>
                    </div>
                </div>
                
                <div class="dropdown-list-content dropdown-list-icons">
                    <?php $__empty_1 = true; $__currentLoopData = $pendingPaymentNotification; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e($notification->data['url']); ?>" class="dropdown-item dropdown-item-unread">
                        <div class="dropdown-item-icon bg-primary text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            <?php echo e($notification->data['message']); ?>

                            <div class="time text-primary"><?php echo e($notification->created_at->diffforhumans()); ?></div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-center"><?php echo e(__('There are no new notifications')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </li>
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg pt-8px">
                <span class="noti-icon">
                    <iconify-icon icon="solar:bell-outline"></iconify-icon>
                </span>
                <span class="badge badge-danger notification-badge"><?php echo e($notifications->count()); ?></span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header"><?php echo e(__('Notifications')); ?>

                    <div class="float-right">
                        <a href="<?php echo e(route('admin.markNotification')); ?>"><?php echo e(__('Mark All As Read')); ?></a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-icons">
                    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e($notification->data['url']); ?>" class="dropdown-item dropdown-item-unread">
                        <div class="dropdown-item-icon bg-primary text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            <?php echo e($notification->data['message']); ?>

                            <div class="time text-primary"><?php echo e($notification->created_at->diffforhumans()); ?></div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-center"><?php echo e(__('There are no new notifications')); ?></p>
                    <?php endif; ?>

                </div>
            </div>
        </li>
        <li class="mx-1 my-auto nav-item dropdown no-arrow">
            <select name="" id="" class="form-control selectric changeLang">
                <?php $__currentLoopData = $language_top; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $top): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($top->short_code); ?>" <?php echo e(languageSelection($top->short_code)); ?>>
                    <?php echo e(__(ucwords($top->name))); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </li>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">

                <span class="d-lg-inline-block text-capitalize"> <?php echo e(auth()->guard('admin')->user()->username); ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">

                <a href="<?php echo e(route('admin.profile')); ?>" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> <?php echo e(__('Profile')); ?>

                </a>

                <a href="<?php echo e(route('admin.logout')); ?>" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> <?php echo e(__('Logout')); ?>

                </a>
            </div>
        </li>
    </ul>
</nav><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/backend/layout/navbar.blade.php ENDPATH**/ ?>