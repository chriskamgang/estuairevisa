@php
$content = content('contact.content');
$elements = element('footer.element');
@endphp

<header class="header-section">
  <div class="header-top-part">
    <div class="container">
      <div class="header-info-part">
        <ul class="header-info-list">
            <li>
                <i class="bi bi-geo-alt-fill"></i>
                <span>{{ @$content->data->location }}</span>
            </li>
            <li>
                <i class="bi bi-envelope-fill"></i>
                <a href="mailto:{{ @$content->data->email }}">{{ @$content->data->email }}</a>
            </li>
        </ul>
        <div class="header-top-right">
            <ul class="header-social-links d-flex align-items-center">
                @foreach (@$elements as $element)
                <li><a href="{{ $element->data->social_link }}" target="_blank" aria-label="Social media link"><i class="{{ $element->data->social_icon }}"></i></a></li>
                @endforeach
            </ul>
            <div class="dropdown">
                <button class="lang-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-globe"></i>
                    <span class="ms-1">{{ __(ucwords(selectedLanguage()->name)) }}</span>
                </button>

                <ul class="dropdown-menu">
                    @foreach ($language_top as $top)
                    <li><a class="dropdown-item {{ languageSelection($top->short_code, 'active') }}"
                            href="{{ route('changeLang', $top->short_code) }}">{{ __(ucwords($top->name))
                            }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div class="header-main-part-wrapper">
    <div class="container">
      <div class="header-main-part">
        <a href="{{route('home')}}" class="logo-part">
            <img src="{{ getFile('logo', $general->logo) }}" alt="logo">
        </a>
        <div class="header-menu-list-wrapper">
            <button type="button" class="mobile-menu-close-btn"><i class="bi bi-x-lg"></i></button>
            <ul class="header-menu-list">
                @foreach (getMenus("headers") as $menu)
                <li><a class="nav-link" href="{{ route('pages', $menu->page->slug) }}">
                    {{ __($menu->page->name) }}</a>
                </li>
                @endforeach

                @if(session('checkout_data'))
                @php
                    $total_cart_item = count(session('checkout_data')['items'])
                @endphp

                <li>
                    <a class="nav-link" href="{{ route('visa.cart') }}">{{__("Cart")}} <span class="badge badge-info">{{$total_cart_item}}</span></a>
                </li>
                @endif
            </ul>
            <div class="d-lg-none px-3 mt-2">
                @auth 
                     <a href="{{ route('user.dashboard') }}" class="btn btn-md btn-primary d-inline-flex align-items-center gap-2">
                      <i class="bi bi-person-circle"></i>
                      {{__("Dashboard")}}
                    </a>
                @else 
                    <a href="{{ route('user.login') }}" class="btn btn-md btn-primary d-inline-flex align-items-center gap-2">
                      <i class="bi bi-person-circle"></i>
                      {{__("Login")}}
                    </a>
                @endauth
            </div>
        </div>
        <button type="button" class="mobile-menu-open-btn ms-auto me-2"><i class="bi bi-list"></i></button>

        
        <div class="d-lg-block d-none">
            @auth 
                 <a href="{{ route('user.dashboard') }}" class="btn btn-md btn-primary d-inline-flex align-items-center gap-2">
                  <i class="bi bi-person-circle"></i>
                  {{__("Dashboard")}}
                </a>
            @else 
                <a href="{{ route('user.login') }}" class="btn btn-md btn-primary d-inline-flex align-items-center gap-2">
                  <i class="bi bi-person-circle"></i>
                  {{__("Login")}}
                </a>
            @endauth
        </div>
        
      </div>
    </div>
  </div>
</header>

