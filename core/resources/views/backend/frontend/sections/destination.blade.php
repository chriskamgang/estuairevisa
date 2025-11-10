@php
$countries = \App\Models\Country::active()->slider()->get();
@endphp
<section class="destination-section py-100">
  <section class="container">
    <div class="row gy-4 align-items-center justify-content-between">
      <div class="col-lg-6 text-lg-start text-center">
        <span class="section-caption"><i class="fa-solid fa-plane-departure"></i> {{ __('Popular Locations') }}</span>
        <h2 class="section-title mt-3">{{ __('You can land anywhere with our Visa') }}</h2>
        <p>{{ __('Explore the world without limits — fast, easy, and reliable visa services at your fingertips.') }}</p>

        <destination-section>
          <div class="location-slider non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
            data-gjs-editable='false' data-gjs-removable='false'
            data-gjs-propagate='["removable","editable","draggable","stylable"]'>
            @foreach($countries as $country)
            <div class="single-slide">
              <div class="location-item">
                <img src="{{getFile('country',$country->image)}}" alt="image">
                <h3 class="title h6">{{$country->name}}</h3>
              </div>
            </div>
            @endforeach
          </div>
        </destination-section>

        <a href="https://wa.me/237640387258?text=Hello,%20I%20would%20like%20to%20consult%20about%20visa%20services" target="_blank" class="btn btn-primary mt-4">
          <i class="bi bi-whatsapp"></i> {{ __('Consulting Now') }}
        </a>
      </div>
      <div class="col-lg-6 text-center ps-xxl-4">
        <img src="assets/images/saudi-arab-map.png" alt="map image">
      </div>
    </div>
  </section>
  <img src="assets/images/elements/plane-3.png" alt="image" class="left-el">
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


      initSlickSlider($('.location-slider'), {
          infinite: true,
          slidesToShow: 5,
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