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
                <a href="{{ route('home') }}" target="_blank"
                    class="bg-primary text-white text-decoration-none p-2 rounded">
                    <i class="fas fa-home  mr-2"></i>
                    <span class="font-weight-bold">{{ __('Visit Frontend') }}</span>
                </a>
            </div>
        </li>
        <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                class="nav-link notification-toggle nav-link-lg pt-8px">
                <span class="noti-icon">
                    <iconify-icon icon="iconamoon:ticket-light"></iconify-icon>
                </span>
                <span class="badge badge-danger notification-badge">{{ $pendingTicketNotifications->count() }}</span> </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
              
                <div class="dropdown-header">{{ __('Ticket Notifications') }}
                    <div class="float-right">
                        <a href="{{ route('admin.markNotification','ticket') }}">{{ __('Mark All As Read') }}</a>
                    </div>
                </div>
                
                
                <div class="dropdown-list-content dropdown-list-icons">
                    @forelse($pendingTicketNotifications as $notification)
                    <a href="{{ $notification->data['url'] }}" class="dropdown-item dropdown-item-unread">
                        <div class="dropdown-item-icon bg-primary text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            {{ $notification->data['message'] }}
                            <div class="time text-primary">{{ $notification->created_at->diffforhumans() }}</div>
                        </div>
                    </a>
                    @empty
                    <p class="text-center">{{ __('There are no new notifications') }}</p>
                    @endforelse
                </div>
            </div>
        </li>

        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg pt-8px">
                <span class="noti-icon">
                    <iconify-icon icon="uit:wallet"></iconify-icon>
                </span>
                <span class="badge badge-danger notification-badge">{{ $pendingPaymentNotification->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">{{ __('Payment Notifications') }}
                    <div class="float-right">
                        <a href="{{ route('admin.markNotification','payment') }}">{{ __('Mark All As Read') }}</a>
                    </div>
                </div>
                
                <div class="dropdown-list-content dropdown-list-icons">
                    @forelse($pendingPaymentNotification as $notification)
                    <a href="{{ $notification->data['url'] }}" class="dropdown-item dropdown-item-unread">
                        <div class="dropdown-item-icon bg-primary text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            {{ $notification->data['message'] }}
                            <div class="time text-primary">{{ $notification->created_at->diffforhumans() }}</div>
                        </div>
                    </a>
                    @empty
                    <p class="text-center">{{ __('There are no new notifications') }}</p>
                    @endforelse
                </div>
            </div>
        </li>
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg pt-8px">
                <span class="noti-icon">
                    <iconify-icon icon="solar:bell-outline"></iconify-icon>
                </span>
                <span class="badge badge-danger notification-badge">{{ $notifications->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">{{ __('Notifications') }}
                    <div class="float-right">
                        <a href="{{ route('admin.markNotification') }}">{{ __('Mark All As Read') }}</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-icons">
                    @forelse($notifications as $notification)
                    <a href="{{ $notification->data['url'] }}" class="dropdown-item dropdown-item-unread">
                        <div class="dropdown-item-icon bg-primary text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            {{ $notification->data['message'] }}
                            <div class="time text-primary">{{ $notification->created_at->diffforhumans() }}</div>
                        </div>
                    </a>
                    @empty
                    <p class="text-center">{{ __('There are no new notifications') }}</p>
                    @endforelse

                </div>
            </div>
        </li>
        <li class="mx-1 my-auto nav-item dropdown no-arrow">
            <select name="" id="" class="form-control selectric changeLang">
                @foreach ($language_top as $top)
                <option value="{{ $top->short_code }}" {{ languageSelection($top->short_code)}}>
                    {{ __(ucwords($top->name)) }}
                </option>
                @endforeach
            </select>
        </li>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">

                <span class="d-lg-inline-block text-capitalize"> {{ auth()->guard('admin')->user()->username }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">

                <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> {{ __('Profile') }}
                </a>

                <a href="{{ route('admin.logout') }}" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                </a>
            </div>
        </li>
    </ul>
</nav>