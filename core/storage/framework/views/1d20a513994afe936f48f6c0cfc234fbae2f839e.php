<ul class="nir-user-menu">
    <li>
        <a href="<?php echo e(route('user.dashboard')); ?>" class="<?php echo e(singleMenu('user.dashboard')); ?>">
            <i class="bi bi-house-door-fill"></i>
            <span><?php echo e(__('Dashboard')); ?></span>
        </a>
    </li>

    <li>
        <a href="<?php echo e(route('user.visa.all')); ?>" class="<?php echo e(singleMenu('user.visa.all')); ?>">
            <i class="bi bi-layers"></i>
            <span><?php echo e(__('Visa Application')); ?></span>
        </a>
    </li>


    <li>
        <a href="<?php echo e(route('user.deposit')); ?>" class="<?php echo e(singleMenu('user.deposit')); ?>">
            <i class="bi bi-wallet2"></i>
            <span><?php echo e(__('Deposit Now')); ?></span>
        </a>
    </li>
    <li>
        <a href="<?php echo e(route('user.deposit.log')); ?>" class="<?php echo e(singleMenu('user.deposit.log')); ?>">
            <i class="bi bi-journal-text"></i>
            <span><?php echo e(__('Deposit Log')); ?></span>
        </a>
    </li>
    <li>
        <a href="<?php echo e(route('user.payment.log')); ?>" class="<?php echo e(singleMenu('user.payment.log')); ?>">
            <i class="bi bi-journal-text"></i>
            <span><?php echo e(__('Payment Log')); ?></span>
        </a>
    </li>

    <li>
        <a href="<?php echo e(route('user.transaction.log')); ?>" class="<?php echo e(singleMenu('user.transaction.log')); ?>">
            <i class="bi bi-file-earmark-text"></i>
            <span><?php echo e(__('Transaction Log')); ?></span>
        </a>
    </li>

    <li>
        <a href="<?php echo e(route('user.referral')); ?>" class="<?php echo e(singleMenu('user.referral')); ?>">
            <i class="bi bi-people"></i>
            <span><?php echo e(__('Referral')); ?></span>
        </a>
    </li>

    <li>
        <a href="<?php echo e(route('user.ticket.index')); ?>" class="<?php echo e(singleMenu('user.ticket.index')); ?>">
            <i class="bi bi-question-circle"></i>
            <span><?php echo e(__('Support')); ?></span>
        </a>
    </li>

    <li>
        <a href="<?php echo e(route('user.profile')); ?>" class="<?php echo e(singleMenu('user.profile')); ?>">
            <i class="bi bi-person-gear"></i>
            <span><?php echo e(__('Profile Settings')); ?></span>
        </a>
    </li>

    <li>
        <a href="<?php echo e(route('user.logout')); ?>"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i>
            <span><?php echo e(__('Logout')); ?></span>
        </a>
        <form id="logout-form" action="<?php echo e(route('user.logout')); ?>" method="POST" class="d-none">
            <?php echo csrf_field(); ?>
        </form>
    </li>

</ul><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/layout/user_sidebar.blade.php ENDPATH**/ ?>