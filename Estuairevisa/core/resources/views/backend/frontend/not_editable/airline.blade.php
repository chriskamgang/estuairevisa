@php
$elements = element('airline.element');
$chunks = $elements->split(2);
@endphp


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


@push('script')
<script>
  $(function () {
    function initSlickSlider($selector, options) {
        if ($selector.hasClass('slick-initialized')) {
            $selector.slick('unslick');
        }
        $selector.slick(options);
    }

    initSlickSlider($('.airline-slider-one'), {
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 5000,
        cssEase: 'linear',
        accessibility: true,
        responsive: [
            {
                breakpoint: 1400,
                settings: { slidesToShow: 4 }
            },
            {
                breakpoint: 480,
                settings: { slidesToShow: 2 }
            }
        ]
    });

    initSlickSlider($('.airline-slider-two'), {
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 5000,
        cssEase: 'linear',
        accessibility: true,
        responsive: [
            {
                breakpoint: 1400,
                settings: { slidesToShow: 4 }
            },
            {
                breakpoint: 480,
                settings: { slidesToShow: 2 }
            }
        ]
    });
});
</script>
@endpush