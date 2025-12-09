@php
$content = content('contact.content');
$contentlink = content('footer.content');
$footersociallink = element('footer.element');

@endphp
<footer class="footer-section">
  <div class="footer-top">
    <div class="container">
      <div class="subscribe-wrapper">
        <div class="row gy-4 justify-content-between align-items-center">
          <div class="col-xxl-4 col-xl-5 col-lg-6">
            <h2 class="title">{{ __('Subscribe Our Newsletter') }}</h2>
            <p class="mb-0">{{ __('Join our newsletter to keep up to date with us!') }}</p>
          </div>
          <div class="col-xxl-6 col-xl-7 col-lg-6">
            <form class="subscribe-form">
              <input type="email" name="subscriber_email" required placeholder="Enter your email address">
              <i class="bi bi-envelope"></i>
              <button type="submit" class="subscribe-btn">{{ __('Subscribe') }}</button>
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
          <a href="{{route('home')}}">
            <img src="{{ getFile('logo', $general->logo) }}" class="nir-footer-logo" alt="logo">
          </a>
          <p class="mt-4 mb-0">{{ translate($contentlink, 'footer_short_description') }}</p>
          <ul class="d-flex flex-wrap align-items-center gap-3 mt-3">
            @forelse (@$footersociallink as $item)
            <li>
              <a href="{{ __($item->data->social_link) }}" target="_blank" aria-label="Social media link"><i
                  class="{{ $item->data->social_icon }}"></i></a>
            </li>
            @empty
            @endforelse
          </ul>
        </div>
        <div class="col-lg-2 col-sm-4">
          <div class="nir-footer-item">
            <h6 class="nir-footer-item-title">{{__("Quick Link")}}</h6>
            <ul class="nir-footer-list">
              @foreach (getMenus('quick_link') as $menu)
              <li><a href="{{ route('pages', $menu->page->slug) }}">{{ __($menu->page->name) }}</a>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="col-lg-2 col-sm-4">
          <div class="nir-footer-item">
            <h6 class="nir-footer-item-title">{{__("Company Info")}}</h6>
            <ul class="nir-footer-list">
              @foreach (getMenus('company') as $menu)
              <li><a href="{{ route('pages', $menu->page->slug) }}">{{ __($menu->page->name) }}</a></li>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-sm-4">
          <div class="nir-footer-item">
            <h6 class="nir-footer-item-title">{{__("GET IN TOUCH")}}</h6>
            <ul class="nir-footer-list">
              <li>
                <span class="caption"><i class="bi bi-telephone-fill"></i></span>
                <span class="description">
                  <a href="tel:{{ @$content->data->phone }}">+{{ __(@$content->data->phone) }}</a>
                </span>
              </li>
              <li class="mt-3">
                <span class="caption"><i class="bi bi-envelope-fill"></i></span>
                <span class="description">
                  <a href="mailto:{{ @$content->data->email }}">{{ __(@$content->data->email) }}</a>
                </span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if ($general->copyright)
  <div class="footer-bottom">
    <div class="container">
      <p class="mb-0 text-center">{{ __($general->copyright) }}</p>
    </div>
  </div>
  @endif
</footer>