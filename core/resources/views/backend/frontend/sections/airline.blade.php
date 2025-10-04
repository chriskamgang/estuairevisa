@php
$elements = element('airline.element');
$chunks = $elements->split(2);
@endphp
<section class="airline-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-header text-center">
          <span class="section-caption"><i class="fa-solid fa-plane-departure"></i> {{ __("Airlines") }}</span>
          <h2 class="section-title mt-3">{{ __("You can travel with any airline") }}</h2>
          <p class="mb-0">{{ __("Freedom to Choose the Airline That Suits You Best") }}</p>
        </div>
      </div>
    </div>

    <div class="row gy-4 align-items-center">
      <div class="col-lg-12">
        <airline-section>
          <div class="airline-slider-wrapper non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
            data-gjs-editable='false' data-gjs-removable='false'
            data-gjs-propagate='["removable","editable","draggable","stylable"]'>
            <div class="airline-slider-one">
              @foreach($chunks[0] ?? [] as $item)
              <div class="single-slide">
                <div class="airline-item">
                  <img src="{{getFile('airline',$item->data->image)}}" alt="image">
                </div>
              </div>
              @endforeach

            </div>
            <div class="airline-slider-two">
              @foreach($chunks[1] ?? [] as $item)
              <div class="single-slide">
                <div class="airline-item">
                  <img src="{{getFile('airline',$item->data->image)}}" alt="image">
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </airline-section>
      </div>
    </div>
  </div>

  <img src="assets/images/elements/2.png" alt="miage" class="right-el">
</section>
@push('script')
<script>
  $(function () {
    setTimeout(() => {
      function initSlickSlider($selector, options) {
        if ($selector.hasClass('slick-initialized')) {
            $selector.slick('unslick');
        }
        $selector.slick(options);
    }

    initSlickSlider($('.airline-slider-one'), {
      infinite: true,
      slidesToShow: 8,
      slidesToScroll: 1,
      arrows: false,
      dots: false,
      autoplay: true,
      autoplaySpeed: 0,
      speed: 5000,
      cssEase: 'linear',
      responsive: [
        {
          breakpoint: 1400,
          settings: {
            slidesToShow: 6,
          }
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 5,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
          }
        }
      ]
    });

    initSlickSlider($('.airline-slider-two'), {
      infinite: true,
      slidesToShow: 8,
      slidesToScroll: 1,
      arrows: false,
      dots: false,
      autoplay: true,
      autoplaySpeed: 0,
      speed: 5000,
      cssEase: 'linear',
      responsive: [
        {
          breakpoint: 1400,
          settings: {
            slidesToShow: 6,
          }
        },
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 5,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
          }
        }
      ]
    });
    }, 500);
});
</script>
@endpush