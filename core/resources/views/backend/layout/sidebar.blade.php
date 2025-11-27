<div class="main-sidebar">
    <aside id="sidebar-wrapper">
         <div class="sidebar-brand">
            <a href="{{ route('admin.home') }}">{{ $general->sitename }}</a>
        </div>
        <ul class="sidebar-menu">

            <li class="nav-item dropdown {{ menuActive('admin.home') }}">
                <a href="{{ route('admin.home') }}" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="solar:home-angle-broken"></iconify-icon>
                    </span>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            <li class="nav-item dropdown {{ menuActive('admin.plans.*') }}">
                <a href="{{ route('admin.plans.index') }}" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="material-symbols:package-outline-rounded"></iconify-icon>
                    </span>
                    <span>{{ __('Manage Plan') }}</span>
                </a>
            </li>


            <li class="nav-item dropdown {{ $navManageApplyActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="mingcute:user-setting-line"></iconify-icon>
                    </span>
                    <span>{{ __('Manage Apply') }}</span></a>
                <ul class="dropdown-menu">
                    <li class="{{$allVisaSubMenuActive ?? ''}}">
                        <a class="nav-link" href="{{ route('admin.visa.list') }}">{{ __('All Apply') }}</a>
                    </li>
                    <li class="{{$pendingVisaSubMenuActive ?? ''}}">
                        <a class="nav-link" href="{{ route('admin.visa.list','pending') }}">{{ __('Pending Apply') }}</a>
                    </li>
                    <li class="{{$reviewVisaSubMenuActive ?? ''}}">
                        <a class="nav-link" href="{{ route('admin.visa.list','review') }}">{{ __('Under Reviews') }}</a>
                    </li>
                    <li class="{{$issuesVisaSubMenuActive ?? ''}}">
                        <a class="nav-link" href="{{ route('admin.visa.list','issues') }}">{{ __('Issues Apply') }}</a>
                    </li>
                    <li class="{{$proccessingVisaSubMenuActive ?? ''}}">
                        <a class="nav-link" href="{{ route('admin.visa.list','processing') }}">{{ __('Processing Apply') }}</a>
                    </li>
                    <li class="{{$completeVisaSubMenuActive ?? ''}}">
                        <a class="nav-link" href="{{ route('admin.visa.list','complete') }}">{{ __('Completed Apply') }}</a>
                    </li>
                    <li class="{{$shippedVisaSubMenuActive ?? ''}}">
                        <a class="nav-link" href="{{ route('admin.visa.list','shipped') }}">{{ __('Shipped Apply') }}</a>
                    </li>
                    <li class="{{$rejectedVisaSubMenuActive ?? ''}}">
                        <a class="nav-link" href="{{ route('admin.visa.list','rejected') }}">{{ __('Rejected Apply') }}</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown {{ menuActive('admin.country.index') }}">
                <a href="{{ route('admin.country.index') }}" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="mynaui:flag"></iconify-icon>
                    </span>
                    <span>{{ __('Country') }}</span>
                </a>
            </li>

            <li class="nav-item dropdown {{ menuActive('admin.field.*') }}">
                <a href="{{ route('admin.field.index') }}" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="mdi:file-outline"></iconify-icon>
                    </span>
                    <span>{{ __('Visa Field') }}</span>
                </a>
            </li>

            <li class="menu-header">{{ __('User Management') }}</li>

            <li class="nav-item dropdown {{ $navManageUserActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="mingcute:user-setting-line"></iconify-icon>
                    </span>
                    <span>{{ __('Manage Users') }} @if ($deactiveUser > 0)
                        <i
                            class="far fa-bell text-danger animate__animated animate__infinite animate__heartBeat animate__slow"></i>
                        @endif
                    </span></a>
                <ul class="dropdown-menu">
                    <li class="{{ $subNavManageUserActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.user') }}">{{ __('All Users') }}</a>
                    </li>

                    <li class="{{ $subNavActiveUserActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.user.filter', 'active') }}">{{ __('Active Users')
                            }}</a>
                    </li>

                    <li class="{{ $subNavDeactiveUserActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.user.filter', 'deactive') }}">{{ __('Deactive Users')
                            }} <span class="badge badge-danger">{{ $deactiveUser }}</span></a>
                    </li>


                </ul>
            </li>

            <li class="menu-header">{{ __('Payment Sections') }}</li>

            <li class="nav-item dropdown {{ $navPaymentGatewayActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="fluent:payment-32-regular"></iconify-icon>
                    </span>
                    <span>{{ __('All Payment Gateway') }}</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ $subNavManualGateway ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.gateway.index') }}">{{ __('Create Manual Gateway')
                            }}</a>
                    </li>
                    <li class="{{ $subNavBankPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.payment.bank') }}">{{ __('Crypto Manual Gateway')
                            }}</a>
                    </li>
                    <li class="{{ $subNavPaypalPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.payment.paypal') }}">{{ __('Paypal') }}</a>
                    </li>

                    <li class="{{ $subNavOrangeMoneyPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.payment.orangemoney') }}">{{ __('Orange Money') }}</a>
                    </li>

                    <li class="{{ $subNavMtnMoneyPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.payment.mtnmoney') }}">{{ __('MTN Mobile Money') }}</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown {{ $navManualPaymentActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="fluent:wallet-32-regular"></iconify-icon>
                    </span>
                    <span>{{ __('Manual Payments') }}</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ $subNavManualPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.manual') }}">{{ __('Manual Payments') }}</a>
                    </li>

                    <li class="{{ $subNavPendingPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.manual.status', 'pending') }}">{{ __('Pending
                            Payments') }}</a>
                    </li>

                    <li class="{{ $subNavAcceptedPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.manual.status', 'accepted') }}">{{ __('Accepted
                            Payments') }}</a>
                    </li>

                    <li class="{{ $subNavRejectedPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.manual.status', 'rejected') }}">{{ __('Rejected
                            Payments') }}</a>
                    </li>

                </ul>
            </li>

            <li class="nav-item dropdown {{ $navDepositPaymentActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="ph:hand-deposit"></iconify-icon>
                    </span>
                    <span>{{ __('Manage Deposits') }}</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ $navAllDepositPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.deposit.all') }}">{{ __('Complete Deposit') }}</a>
                    </li>

                    <li class="{{ $navPendingDepositPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.deposit.log') }}">{{ __('Pending Deposit') }}</a>
                    </li>

                    <li class="{{ $navRejectedDepositPaymentActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.deposit.rejected') }}">{{ __('Rejected Deposit')
                            }}</a>
                    </li>
                </ul>
            </li>

 

            <li class="nav-item mb-3 dropdown {{ $navReportActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="solar:document-text-linear"></iconify-icon>
                    </span>
                    <span>{{ __('All Reports') }}</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ $subNavPaymentReportActiveClass ?? '' }}"><a class="nav-link"
                            href="{{ route('admin.payment.report') }}">{{ __('Payment Reports') }}</a>
                    </li>
                    

                    <li class="{{ $subNavTransactionActiveClass ?? '' }}">
                        <a href="{{ route('admin.transaction') }}" class="nav-link ">{{ __('Transaction Reports') }}</a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-menu-caption">{{ __('Settings') }}</li>

            <li class="nav-item dropdown {{ $navEmailManagerActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="tabler:mail-cog"></iconify-icon>
                    </span>
                    <span>{{ __('Email Manager') }}</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ $subNavEmailConfigActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.email.config') }}">{{ __('Email Configure') }}</a>
                    </li>
                    <li class="{{ $subNavEmailTemplatesActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.email.templates') }}">{{ __('Email Templates') }}</a>
                    </li>
                </ul>
            </li>


            <li class="menu-header">{{ __('System Settings') }}</li>

            <li class="nav-item dropdown {{ $navGeneralSettingsActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="clarity:network-settings-line"></iconify-icon>
                    </span>
                    <span>{{ __('Manage Settings') }}</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ $subNavGeneralSettingsActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.general.setting') }}">{{ __('General Settings') }}</a>
                    </li>
                    <li class="{{ $subNavPreloaderActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.general.preloader') }}">{{ __('Preloader Setting')
                            }}</a>
                    </li>
                    <li class="{{ $subNavAnalyticsActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.general.analytics') }}">{{ __('Google Analytics')
                            }}</a>
                    </li>
                    <li class="{{ $subNavSocialiteActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.general.socialite') }}">{{ __('Socialite Setting')
                            }}</a>
                    </li>
                    <li class="{{ $subNavDeeplActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.general.deepl') }}">
                            <i class="fa fa-language"></i> {{ __('DeepL Translation API') }}
                        </a>
                    </li>
                    <li class="{{ $subNavCookieActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.general.cookie') }}">{{ __('Cookie Consent') }}</a>
                    </li>
                    <li class="{{ $subNavRecaptchaActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.general.recaptcha') }}">{{ __('Google Recaptcha')
                            }}</a>
                    </li>
                    <li class="{{ $subNavSEOManagerActiveClass ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.general.seo') }}">{{ __('Global SEO Manager') }}</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown {{ $navManageLanguageActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="mdi:language"></iconify-icon>
                    </span>
                    <span>{{ __('Manage Language') }}</span></a>

                <ul class="dropdown-menu">

                    <li class="{{ $subNavManageLanguageActiveClass ?? '' }}"><a class="nav-link"
                            href="{{ route('admin.language.index') }}">{{ __('All Language') }}</a>
                    </li>
                </ul>

            </li>


            <li class="sidebar-menu-caption">{{ __('Others') }}</li>

            <li class="nav-item dropdown {{ menuActive('admin.frontend.pages') }}">
                <a href="{{ route('admin.frontend.pages') }}" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="fluent:layout-row-two-settings-32-regular"></iconify-icon>
                    </span>
                    <span>{{ __('Manage Page') }}</span>
                </a>
            </li>

            <li class="nav-item dropdown {{ menuActive('admin.menu') }}">
                <a href="{{ route('admin.menu') }}" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="fluent:layout-row-two-settings-32-regular"></iconify-icon>
                    </span>
                    <span>{{ __('Manage Menu') }}</span>
                </a>
            </li>
            
            <li class="nav-item dropdown {{ menuActive('admin.contact.list') }}">
                <a href="{{ route('admin.contact.list') }}" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="fluent:layout-row-two-settings-32-regular"></iconify-icon>
                    </span>
                    <span>{{ __('Contact List') }}</span>
                </a>
            </li>

            <li class="nav-item dropdown {{ $navManagePagesActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="mynaui:layout"></iconify-icon>
                    </span>
                    <span>{{ __('Manage Section') }}</span>
                </a>

                <ul class="dropdown-menu">
                    @forelse($urlSections as $key => $section)
                    <li class="">
                        <a class="nav-link" href="{{ route('admin.frontend.section.manage', ['name' => $key]) }}">{{
                            frontendFormatter($key) . ' Section' }}</a>
                    </li>
                    @empty

                    @endif
                </ul>
            </li>

            <li class="nav-item dropdown {{ $navTicketActiveClass ?? '' }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <span class="icon">
                        <iconify-icon icon="iconamoon:ticket-light"></iconify-icon>
                    </span>
                    <span>{{ __('Support Ticket') }}</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ $ticketList ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.ticket.index') }}">{{ __('All Tickets') }}</a>
                    </li>
                    <li class="{{ $pendingTicket ?? '' }}">
                        <a class="nav-link" href="{{ route('admin.ticket.pendingList') }}">{{ __('Pending Ticket')
                            }}</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown {{ menuActive('admin.subscribers') }}">
                <a href="{{ route('admin.subscribers') }}" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="fluent-mdl2:subscribe"></iconify-icon>
                    </span>
                    <span>{{ __('Newsletter Subscriber') }}</span>
                </a>
            </li>

            <li class="nav-item dropdown {{ menuActive('admin.general.cacheclear') }}">
                <a href="{{ route('admin.general.cacheclear') }}" class="nav-link ">
                    <span class="icon">
                        <iconify-icon icon="octicon:rocket-24"></iconify-icon>
                    </span>
                    <span>{{ __('Cache Clear') }}</span>
                </a>
            </li>
            <span>{{ __('VERSION 1.0.0') }}</span>
        </ul>
    </aside>
</div>