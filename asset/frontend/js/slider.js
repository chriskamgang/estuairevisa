function initAllSlickSliders(context = document) {
    function initSlickSlider($selector, options) {
        if ($selector.hasClass('slick-initialized')) {
            $selector.slick('unslick');
        }
        $selector.slick(options);
    }

    const $ = context.defaultView.jQuery;

    initSlickSlider($(context).find('.airline-slider-one'), {
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 5000,
        cssEase: 'linear',
        accessibility: false,
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 4 } },
            { breakpoint: 480, settings: { slidesToShow: 2 } }
        ]
    });

    initSlickSlider($(context).find('.airline-slider-two'), {
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 5000,
        cssEase: 'linear',
        accessibility: false,
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 4 } },
            { breakpoint: 480, settings: { slidesToShow: 2 } }
        ]
    }); // Same as above

    initSlickSlider($(context).find('.testimonial-slider'), {
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: `<span class="testi-prev"><i class="bi bi-arrow-left"></i></span>`,
        nextArrow: `<span class="testi-next"><i class="bi bi-arrow-right"></i></span>`,
        dots: false,
        autoplay: true,
        responsive: [
            { breakpoint: 1200, settings: { slidesToShow: 2, arrows: false, dots: true } },
            { breakpoint: 768, settings: { slidesToShow: 1, arrows: false, dots: true } }
        ]
    });

    initSlickSlider($(context).find('.location-slider'), {
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
            { breakpoint: 1400, settings: { slidesToShow: 4 } },
            { breakpoint: 480, settings: { slidesToShow: 2 } }
        ]
    });
}
