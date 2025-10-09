<div class="main-sidebar">
    <aside id="sidebar-wrapper">
         <div class="sidebar-brand">
            <a href="<?php echo e(route('admin.home')); ?>"><?php echo e($general->sitename); ?></a>
        </div>
        <ul class="sidebar-menu">

            <li class="nav-item dropdown <?php echo e(menuActive('admin.home')); ?>">
                <a href="<?php echo e(route('admin.home')); ?>" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="solar:home-angle-broken"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Dashboard')); ?></span>
                </a>
            </li>

            <li class="nav-item dropdown <?php echo e(menuActive('admin.plans.*')); ?>">
                <a href="<?php echo e(route('admin.plans.index')); ?>" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="material-symbols:package-outline-rounded"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Manage Plan')); ?></span>
                </a>
            </li>


            <li class="nav-item dropdown <?php echo e($navManageApplyActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="mingcute:user-setting-line"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Manage Apply')); ?></span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo e($allVisaSubMenuActive ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.visa.list')); ?>"><?php echo e(__('All Apply')); ?></a>
                    </li>
                    <li class="<?php echo e($pendingVisaSubMenuActive ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.visa.list','pending')); ?>"><?php echo e(__('Pending Apply')); ?></a>
                    </li>
                    <li class="<?php echo e($reviewVisaSubMenuActive ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.visa.list','review')); ?>"><?php echo e(__('Under Reviews')); ?></a>
                    </li>
                    <li class="<?php echo e($issuesVisaSubMenuActive ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.visa.list','issues')); ?>"><?php echo e(__('Issues Apply')); ?></a>
                    </li>
                    <li class="<?php echo e($proccessingVisaSubMenuActive ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.visa.list','processing')); ?>"><?php echo e(__('Processing Apply')); ?></a>
                    </li>
                    <li class="<?php echo e($completeVisaSubMenuActive ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.visa.list','complete')); ?>"><?php echo e(__('Completed Apply')); ?></a>
                    </li>
                    <li class="<?php echo e($shippedVisaSubMenuActive ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.visa.list','shipped')); ?>"><?php echo e(__('Shipped Apply')); ?></a>
                    </li>
                    <li class="<?php echo e($rejectedVisaSubMenuActive ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.visa.list','rejected')); ?>"><?php echo e(__('Rejected Apply')); ?></a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php echo e(menuActive('admin.country.index')); ?>">
                <a href="<?php echo e(route('admin.country.index')); ?>" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="mynaui:flag"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Country')); ?></span>
                </a>
            </li>

            <li class="nav-item dropdown <?php echo e(menuActive('admin.field.*')); ?>">
                <a href="<?php echo e(route('admin.field.index')); ?>" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="mdi:file-outline"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Visa Field')); ?></span>
                </a>
            </li>

            <li class="menu-header"><?php echo e(__('User Management')); ?></li>

            <li class="nav-item dropdown <?php echo e($navManageUserActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="mingcute:user-setting-line"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Manage Users')); ?> <?php if($deactiveUser > 0): ?>
                        <i
                            class="far fa-bell text-danger animate__animated animate__infinite animate__heartBeat animate__slow"></i>
                        <?php endif; ?>
                    </span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo e($subNavManageUserActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.user')); ?>"><?php echo e(__('All Users')); ?></a>
                    </li>

                    <li class="<?php echo e($subNavActiveUserActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.user.filter', 'active')); ?>"><?php echo e(__('Active Users')); ?></a>
                    </li>

                    <li class="<?php echo e($subNavDeactiveUserActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.user.filter', 'deactive')); ?>"><?php echo e(__('Deactive Users')); ?> <span class="badge badge-danger"><?php echo e($deactiveUser); ?></span></a>
                    </li>


                </ul>
            </li>

            <li class="menu-header"><?php echo e(__('Payment Sections')); ?></li>

            <li class="nav-item dropdown <?php echo e($navPaymentGatewayActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="fluent:payment-32-regular"></iconify-icon>
                    </span>
                    <span><?php echo e(__('All Payment Gateway')); ?></span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo e($subNavManualGateway ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.gateway.index')); ?>"><?php echo e(__('Create Manual Gateway')); ?></a>
                    </li>
                    <li class="<?php echo e($subNavBankPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.payment.bank')); ?>"><?php echo e(__('Crypto Manual Gateway')); ?></a>
                    </li>
                    <li class="<?php echo e($subNavPaypalPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.payment.paypal')); ?>"><?php echo e(__('Paypal')); ?></a>
                    </li>

                    <li class="<?php echo e($subNavOrangeMoneyPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.payment.orangemoney')); ?>"><?php echo e(__('Orange Money')); ?></a>
                    </li>

                    <li class="<?php echo e($subNavMtnMoneyPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.payment.mtnmoney')); ?>"><?php echo e(__('MTN Mobile Money')); ?></a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php echo e($navManualPaymentActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="fluent:wallet-32-regular"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Manual Payments')); ?></span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo e($subNavManualPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.manual')); ?>"><?php echo e(__('Manual Payments')); ?></a>
                    </li>

                    <li class="<?php echo e($subNavPendingPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.manual.status', 'pending')); ?>"><?php echo e(__('Pending
                            Payments')); ?></a>
                    </li>

                    <li class="<?php echo e($subNavAcceptedPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.manual.status', 'accepted')); ?>"><?php echo e(__('Accepted
                            Payments')); ?></a>
                    </li>

                    <li class="<?php echo e($subNavRejectedPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.manual.status', 'rejected')); ?>"><?php echo e(__('Rejected
                            Payments')); ?></a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown <?php echo e($navDepositPaymentActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="ph:hand-deposit"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Manage Deposits')); ?></span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo e($navAllDepositPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.deposit.all')); ?>"><?php echo e(__('Complete Deposit')); ?></a>
                    </li>

                    <li class="<?php echo e($navPendingDepositPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.deposit.log')); ?>"><?php echo e(__('Pending Deposit')); ?></a>
                    </li>

                    <li class="<?php echo e($navRejectedDepositPaymentActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.deposit.rejected')); ?>"><?php echo e(__('Rejected Deposit')); ?></a>
                    </li>
                </ul>
            </li>

 

            <li class="nav-item mb-3 dropdown <?php echo e($navReportActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="solar:document-text-linear"></iconify-icon>
                    </span>
                    <span><?php echo e(__('All Reports')); ?></span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo e($subNavPaymentReportActiveClass ?? ''); ?>"><a class="nav-link"
                            href="<?php echo e(route('admin.payment.report')); ?>"><?php echo e(__('Payment Reports')); ?></a>
                    </li>
                    

                    <li class="<?php echo e($subNavTransactionActiveClass ?? ''); ?>">
                        <a href="<?php echo e(route('admin.transaction')); ?>" class="nav-link "><?php echo e(__('Transaction Reports')); ?></a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-menu-caption"><?php echo e(__('Settings')); ?></li>

            <li class="nav-item dropdown <?php echo e($navEmailManagerActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="tabler:mail-cog"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Email Manager')); ?></span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo e($subNavEmailConfigActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.email.config')); ?>"><?php echo e(__('Email Configure')); ?></a>
                    </li>
                    <li class="<?php echo e($subNavEmailTemplatesActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.email.templates')); ?>"><?php echo e(__('Email Templates')); ?></a>
                    </li>
                </ul>
            </li>


            <li class="menu-header"><?php echo e(__('System Settings')); ?></li>

            <li class="nav-item dropdown <?php echo e($navGeneralSettingsActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="clarity:network-settings-line"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Manage Settings')); ?></span></a>
                <ul class="dropdown-menu">
                    <li class="<?php echo e($subNavGeneralSettingsActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.general.setting')); ?>"><?php echo e(__('General Settings')); ?></a>
                    </li>
                    <li class="<?php echo e($subNavPreloaderActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.general.preloader')); ?>"><?php echo e(__('Preloader Setting')); ?></a>
                    </li>
                    <li class="<?php echo e($subNavAnalyticsActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.general.analytics')); ?>"><?php echo e(__('Google Analytics')); ?></a>
                    </li>
                    <li class="<?php echo e($subNavSocialiteActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.general.socialite')); ?>"><?php echo e(__('Socialite Setting')); ?></a>
                    </li>
                    <li class="<?php echo e($subNavCookieActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.general.cookie')); ?>"><?php echo e(__('Cookie Consent')); ?></a>
                    </li>
                    <li class="<?php echo e($subNavRecaptchaActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.general.recaptcha')); ?>"><?php echo e(__('Google Recaptcha')); ?></a>
                    </li>
                    <li class="<?php echo e($subNavSEOManagerActiveClass ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.general.seo')); ?>"><?php echo e(__('Global SEO Manager')); ?></a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown <?php echo e($navManageLanguageActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="mdi:language"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Manage Language')); ?></span></a>

                <ul class="dropdown-menu">

                    <li class="<?php echo e($subNavManageLanguageActiveClass ?? ''); ?>"><a class="nav-link"
                            href="<?php echo e(route('admin.language.index')); ?>"><?php echo e(__('All Language')); ?></a>
                    </li>
                </ul>

            </li>


            <li class="sidebar-menu-caption"><?php echo e(__('Others')); ?></li>

            <li class="nav-item dropdown <?php echo e(menuActive('admin.frontend.pages')); ?>">
                <a href="<?php echo e(route('admin.frontend.pages')); ?>" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="fluent:layout-row-two-settings-32-regular"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Manage Page')); ?></span>
                </a>
            </li>

            <li class="nav-item dropdown <?php echo e(menuActive('admin.menu')); ?>">
                <a href="<?php echo e(route('admin.menu')); ?>" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="fluent:layout-row-two-settings-32-regular"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Manage Menu')); ?></span>
                </a>
            </li>
            
            <li class="nav-item dropdown <?php echo e(menuActive('admin.contact.list')); ?>">
                <a href="<?php echo e(route('admin.contact.list')); ?>" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="fluent:layout-row-two-settings-32-regular"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Contact List')); ?></span>
                </a>
            </li>

            <li class="nav-item dropdown <?php echo e($navManagePagesActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="mynaui:layout"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Manage Section')); ?></span>
                </a>

                <ul class="dropdown-menu">
                    <?php $__empty_1 = true; $__currentLoopData = $urlSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="">
                        <a class="nav-link" href="<?php echo e(route('admin.frontend.section.manage', ['name' => $key])); ?>"><?php echo e(frontendFormatter($key) . ' Section'); ?></a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <?php endif; ?>
                </ul>
            </li>

            <li class="nav-item dropdown <?php echo e($navTicketActiveClass ?? ''); ?>">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="iconamoon:ticket-light"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Support Ticket')); ?></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="<?php echo e($ticketList ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.ticket.index')); ?>"><?php echo e(__('All Tickets')); ?></a>
                    </li>
                    <li class="<?php echo e($pendingTicket ?? ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('admin.ticket.pendingList')); ?>"><?php echo e(__('Pending Ticket')); ?></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown <?php echo e(menuActive('admin.subscribers')); ?>">
                <a href="<?php echo e(route('admin.subscribers')); ?>" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="fluent-mdl2:subscribe"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Newsletter Subscriber')); ?></span>
                </a>
            </li>

            <li class="nav-item dropdown <?php echo e(menuActive('admin.general.cacheclear')); ?>">
                <a href="<?php echo e(route('admin.general.cacheclear')); ?>" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="octicon:rocket-24"></iconify-icon>
                    </span>
                    <span><?php echo e(__('Cache Clear')); ?></span>
                </a>
            </li>
            <span><?php echo e(__('VERSION 1.0.0')); ?></span>
        </ul>
    </aside>
</div><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/backend/layout/sidebar.blade.php ENDPATH**/ ?>