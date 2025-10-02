@php
$countries = \App\Models\Country::active()->slider()->get();
@endphp

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

@push('script')
<script>
    $(function(){
            'use strict'

            function initSlickSlider($selector, options) {

                if ($selector.hasClass('slick-initialized')) {
                    $selector.slick('unslick');
                }
                $selector.slick(options);
            }


            initSlickSlider($('.location-slider'), {
                infinite: true,
                slidesToShow: 4,
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
        })
</script>
@endpush