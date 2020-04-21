wow = new WOW(
    {
        boxClass:     'wow',      // default
        animateClass: 'animated', // default
        offset:       0,          // default
        mobile:       true,       // default
        live:         true        // default
    }
)
wow.init();

jQuery(document).ready(function($) {
    $(".regular2").slick({
        dots: false,
        autoplay:false,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        responsive: [

            {
                breakpoint: 549,
                settings: {

                    slidesToShow: 2
                }
            }
        ]

    });
});