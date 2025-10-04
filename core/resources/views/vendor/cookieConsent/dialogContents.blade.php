
<div class="js-cookie-consent cookie-consent cookie-modal">

    <div class="cookies-card__icon">
        <i class="fas fa-cookie-bite"></i>
      </div>

    <p class="cookie-consent__message text-white">
        <span class="cookie-consent__message-text">{{__($general->cookie_text)}}</span> <a href="{{$general->cookie_link}}" class="site-color">{{ __("know more") }}</a>
    </p>

    <div class="d-flex flex-wrap align-items-center gap-3">
        <button class="js-cookie-consent-agree cookie-consent__agree btn text-white">
            {{ __($general->button_text) }}
        </button>
        <button class="js-cookie-consent-agree cookie-consent__agree btn text-white">
            {{ __('Cancel') }}
        </button>
    </div>
</div>
