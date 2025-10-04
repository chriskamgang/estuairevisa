<ul class="nir-user-menu">
    <li>
        <a href="{{ route('user.dashboard') }}" class="{{ singleMenu('user.dashboard') }}">
            <i class="bi bi-house-door-fill"></i>
            <span>{{ __('Dashboard') }}</span>
        </a>
    </li>

    <li>
        <a href="{{ route('user.visa.all') }}" class="{{ singleMenu('user.visa.all') }}">
            <i class="bi bi-layers"></i>
            <span>{{ __('Visa Application') }}</span>
        </a>
    </li>


    <li>
        <a href="{{ route('user.deposit') }}" class="{{ singleMenu('user.deposit') }}">
            <i class="bi bi-wallet2"></i>
            <span>{{ __('Deposit Now') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user.deposit.log') }}" class="{{ singleMenu('user.deposit.log') }}">
            <i class="bi bi-journal-text"></i>
            <span>{{ __('Deposit Log') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('user.payment.log') }}" class="{{ singleMenu('user.payment.log') }}">
            <i class="bi bi-journal-text"></i>
            <span>{{ __('Payment Log') }}</span>
        </a>
    </li>

    <li>
        <a href="{{ route('user.transaction.log') }}" class="{{ singleMenu('user.transaction.log') }}">
            <i class="bi bi-file-earmark-text"></i>
            <span>{{ __('Transaction Log') }}</span>
        </a>
    </li>

    <li>
        <a href="{{ route('user.referral') }}" class="{{ singleMenu('user.referral') }}">
            <i class="bi bi-people"></i>
            <span>{{ __('Referral') }}</span>
        </a>
    </li>

    <li>
        <a href="{{ route('user.ticket.index') }}" class="{{ singleMenu('user.ticket.index') }}">
            <i class="bi bi-question-circle"></i>
            <span>{{ __('Support') }}</span>
        </a>
    </li>

    <li>
        <a href="{{ route('user.profile') }}" class="{{ singleMenu('user.profile') }}">
            <i class="bi bi-person-gear"></i>
            <span>{{ __('Profile Settings') }}</span>
        </a>
    </li>

    <li>
        <a href="{{ route('user.logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i>
            <span>{{ __('Logout') }}</span>
        </a>
        <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>

</ul>