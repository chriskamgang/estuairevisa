@php
$content = content('testimonial.content');
$elements = element('testimonial.element');
@endphp


<div class="testimonial-slider">
    @foreach ($elements as $element)
    <div class="testimonial-slide">
        <div class="testimonial-item">
            <span class="icon"><i class="bi bi-quote"></i></span>
            <p class="testimonial-details">{{ translate($element, 'answer') }}</p>

            <div class="client">
                <img src="{{ getFile('testimonial', $element->data->image) }}" alt="image" class="thumb">
                <div class="content">
                    <h3 class="name">{{ translate($element, 'client_name') }}</h3>
                    <p class="mb-0 ratings">
                        @php
                        $notfill = 5 - $element->data->review;
                        @endphp
                        @for ($i = 1; $i <= $element->data->review; $i++)
                            <i class="bi bi-star-fill"></i>
                            @endfor
                            @for ($i = 1; $i <= $notfill; $i++) <i class="bi bi-star"></i>
                                @endfor
                    </p>
                </div>
            </div>
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

    initSlickSlider($('.testimonial-slider'), {
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: `<span class="testi-prev"><i class="bi bi-arrow-left"></i></span>`,
        nextArrow: `<span class="testi-next"><i class="bi bi-arrow-right"></i></span>`,
        dots: false,
        autoplay: true,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                    dots: true,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                    dots: true,
                }
            }
        ]
    });
});
</script>
@endpush