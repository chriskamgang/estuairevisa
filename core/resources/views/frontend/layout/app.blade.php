<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name='description' content="{{ $general->seo_description }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph / Facebook / WhatsApp / LinkedIn / Telegram -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $general->sitename ?? 'Immigration de l\'Estuaire' }}">
    <meta property="og:description" content="{{ $general->seo_description ?? 'Obtenez votre visa rapidement pour plus de 15 destinations. Service professionnel avec taux d\'approbation de 98%. Traitement en 48-72 heures.' }}">
    <meta property="og:image" content="{{ url('asset/images/logo/logo.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:type" content="image/png">
    <meta property="og:site_name" content="{{ $general->sitename ?? 'Immigration de l\'Estuaire' }}">
    <meta property="og:locale" content="fr_FR">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="{{ $general->sitename ?? 'Immigration de l\'Estuaire' }}">
    <meta name="twitter:description" content="{{ $general->seo_description ?? 'Obtenez votre visa rapidement pour plus de 15 destinations. Service professionnel avec taux d\'approbation de 98%. Traitement en 48-72 heures.' }}">
    <meta name="twitter:image" content="{{ url('asset/images/logo/logo.png') }}">

    <!-- Additional Meta Tags for better social sharing -->
    <meta name="author" content="{{ $general->sitename ?? 'Immigration de l\'Estuaire' }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">

    <link rel="shortcut icon" type="image/png" href="{{ getFile('icon', $general->favicon) }}">

    <title>
        @if ($general->sitename)
        {{ __($general->sitename) }}
        @endif

    </title>

    <link rel="stylesheet" href="{{ asset('asset/frontend/css/cookie.css') }}">
    <link rel="stylesheet" href="{{asset('asset/frontend/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/frontend/css/bootstrap-icons.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/frontend/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/frontend/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/frontend/css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('asset/frontend/css/izitoast.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/frontend/css/style.css')}}">



    @stack('style')

    <link rel="stylesheet"
        href="{{ asset('asset/frontend/css/color.php?primary_color=' . str_replace('#', '', $general->primary_color)) }}">
</head>


<body>

    @if ($general->preloader_status)
    <div class="rt-preloader">
      <div class="rt-cube-grid">
        <div class="rt-cube rt-cube1"></div>
        <div class="rt-cube rt-cube2"></div>
        <div class="rt-cube rt-cube3"></div>
        <div class="rt-cube rt-cube4"></div>
        <div class="rt-cube rt-cube5"></div>
        <div class="rt-cube rt-cube6"></div>
        <div class="rt-cube rt-cube7"></div>
        <div class="rt-cube rt-cube8"></div>
        <div class="rt-cube rt-cube9"></div>
      </div>
    </div>
    @endif


    @if ($general->allow_modal)
    @include('cookieConsent::index')
    @endif


    @if ($general->analytics_status)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $general->analytics_key }}"></script>
    <script>
        'use strict'
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag("js", new Date());
            gtag("config", "{{ $general->analytics_key }}");
    </script>
    @endif



    @yield('content')


    <button type="button" class="top-action-btn">
      <span class="icon">
        <i class="bi bi-arrow-up"></i>
      </span>
      <span class="caption">Top</span>
    </button>


    <script src="{{ asset('asset/frontend/js/jquery-3.7.1.min.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/aos.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/apexcharts.min.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/iconify-icon.min.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/intlTelInput.min.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/magnifc-popup.min.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/select2.min.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/slick.min.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/izitoast.min.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/jquery.basictable.min.js')}}"></script>
    <script src="{{ asset('asset/frontend/js/app.js')}}"></script>

    @stack('script')

    @include('frontend.notify')
      <script>
    $(function(){

            'use strict';
            $(".changeLang").on("change", function() {
                let url = $(this).find('option:selected').data('action');
                window.location.href = url;
            });
            
             $(document).on('click', '.subscribe-btn', function(e) {
                'use strict'
                    e.preventDefault();
                    const email = $("input[name=subscriber_email]").val();

                    if(!email){
                       iziToast.error({
                                message: "Email is required",
                                position: 'topRight',
                            });
                      return false;
                    }
                    var url = "{{ route('subscribe') }}";
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    

        
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            email: email,
                        },
                        success: (data) => {
                            iziToast.success({
                                message: data.message,
                                position: 'topRight',
                            });
                            $('input[name=subscriber_email]').val('');
        
                        },
                        error: (error) => {
                            if (typeof(error.responseJSON.errors.email) !== "undefined") {
                                iziToast.error({
                                    message: error.responseJSON.errors.email,
                                    position: 'topRight',
                                });
                            }
        
                        }
                })

            $(".changeLang").on("change", function() {
                let url = $(this).find('option:selected').data('action');
                window.location.href = url;
            });


              $(window).on("scroll", function () {
                if ($(this).scrollTop() > 200) {
                  $(".top-action-btn").fadeIn(200);
                } else {
                  $(".top-action-btn").fadeOut(200);
                }
              });
        });
        
        document.addEventListener("DOMContentLoaded", function () {
          const preloader = document.querySelector(".rt-preloader");
          if (preloader) {
            preloader.style.display = "none";
          }
        });
        
        



        })
  </script>

  <!-- Firebase Cloud Messaging -->
  @auth
  <script type="module" src="{{ asset('asset/frontend/js/firebase-init.js') }}"></script>
  @endauth
</body>
</html>