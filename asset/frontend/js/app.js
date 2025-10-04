(function () {
  'use strict';

    //preloader
  $(".rt-preloader").delay(300).animate({
    "opacity" : "0"
    }, 300, function() {
    $(".rt-preloader").css("display","none");
  });

  // Bootstrap tooltip js 
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

  // Responsive table js 
  $('.responsive-table').basictable({
    breakpoint: 991,
  });

  // Show or hide the sticky footer button
  $(window).on("scroll", function () {
    if ($(this).scrollTop() > 200) {
      $(".top-action-btn").fadeIn(200);
    } else {
      $(".top-action-btn").fadeOut(200);
    }
    
    if( $(window).scrollTop() > 50){  
        $(".header-section").addClass("animated fadeInDown header-fixed");
    }
    else {
        $(".header-section").removeClass("animated fadeInDown header-fixed");
    }
  });

  // Animate the scroll to top
  $(".top-action-btn").on("click", function (event) {
    event.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, 300);
  });

  $(".mobile-menu-open-btn").on("click", function () {
    $(".header-menu-list-wrapper").addClass("active");
  });

  $(".mobile-menu-close-btn").on("click", function () {
    $(".header-menu-list-wrapper").removeClass("active");
  });


})();