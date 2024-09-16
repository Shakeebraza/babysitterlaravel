@php $lang = session()->get('locale'); @endphp
    <!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Babysitter-App') }}</title>
    <meta name="description" content="{{__(trans('messages.landing_page_description'))}}" />
    <meta name="keywords" content="Babysitter, Nanny, Babysitter-App, Babysitter, App, Nanny Search, Suche Nanny,
    Babysitter Search, Babysitter Suche, Kinderbetreuung, verified babysitters, verifizierte Babysitter,
    Freunde und Familie einspannen, balancing work and parenting, daytime help,
    Hilfe für den Alltag, get help from family and friends, lokale Babysitter Services, sichere Kinderbetreuung,
    Notfall-Babysitter, Kinderbetreuer Bewertungen, Kinderbetreuungsoptionen, Elternhilfe Apps,
    vertrauenswürdige Nanny-Services, Kinderbetreuung buchen, erfahrene Babysitter online,
    Babysitter Job finden, Kinderbetreuungskosten, Babysitter in der Nähe" />
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('assets/website/images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/ajax-loader.gif">
    <link rel="stylesheet" href="{{ URL::asset('assets/website/css/stellarnav.min.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="{{ URL::asset('assets/website/css/style.css') }}">
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TLTN9DV9');</script>
    <!-- End Google Tag Manager -->
    <style>
        input::placeholder { /* Most modern browsers support this now. */
            color: #472894 !important;
            opacity: 0.5!important;
        }
        textarea::placeholder { /* Most modern browsers support this now. */
            color: #472894 !important;
            opacity: 0.5!important;
        }
        .error{color:#ff0000;}

        .carousel .carousel-item img {
            left: -9999px;
            right: -9999px;
            margin: 0 auto;
        }

        .carousel-control-next,
        .carousel-control-prev,
        .carousel-indicators {
            filter: invert(100%);
        }
    </style>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TLTN9DV9"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div id="app">
    <!-- header-start -->
    <header>
        <div class="container">
            <div class="row">
                <div class="logoMenuInner">
                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12">
                        <div class="logoMain">
                            <a href="{{url('/')}}" class="logo">
                            <img src="{{ URL::asset('assets/website/images/logo.png') }}" alt="logo" width="200" height="50">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                        <div class="menuBtns">
                            <div class="stellarnav">
                                <ul>
                                    <li>
                                        <a href="#featuresMain">{{__(trans('messages.for_what'))}}</a>
                                    </li>
                                    <li>
                                        <a href="#aboutMain">{{__(trans('messages.about_app'))}}</a>
                                    </li>
                                    <li>
                                        <a href="#appStoreMain">{{__(trans('messages.download_app'))}}</a>
                                    </li>
                                    <li>
                                        <a href="#testimonialMain">{{__(trans('messages.testimonial'))}}</a>
                                    </li>
                                    <li>
                                        <a href="#contactMain">{{__(trans('messages.contact_us'))}}</a>
                                    </li>
                                    <li>
                                        <a href="#aboutUs">{{__(trans('messages.about_us'))}}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="language-dropBox">
                                <select id="langSelection">
                                    <option value="en" @if(app()->getLocale() == 'en') selected @endif>{{__(trans('messages.english'))}}</option>
                                    <option value="de" @if(app()->getLocale() == 'de') selected @endif>{{__(trans('messages.german'))}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->
        @yield('content')
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.js"></script>
    <script src="{{ URL::asset('assets/website/js/stellarnav.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="{{ URL('assets/dist/js/jquery.validate.js')}}"></script>
    <script src="{{ URL::asset('assets/website/js/custom.js') }}"></script>
    <script>
        $('#langSelection').on('change', function(){
            var selectedLang = $(this).val();
            var currentPath = window.location.pathname;
            var newPath = currentPath.replace(/^\/[\w]{2}\//, '/' + selectedLang + '/');
            if (newPath !== currentPath) {
                window.location.pathname = newPath;
            }
        });

        $(document).ready(function() {
            $("#contactUsForms").validate({
                rules: {
                    full_name: "required",
                    email: "required",
                    message: "required",
                },
                messages: {
                    full_name: "{{__(trans('messages.name_validation'))}}",
                    email: "{{__(trans('messages.email_validation'))}}",
                    message: "{{__(trans('messages.message_validation'))}}",
                },
                submitHandler: function(form) {
                    $('#contactBtn').val('Process...');
                    var formdata = $('#contactUsForms').serialize();
                    $.ajax({
                        url: '{{route('sendContactUs')}}',
                        type: "post",
                        data: formdata,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data){
                            if(data == 1){
                                $('#contactBtn').val('Send');
                                $('#contactUsForms')[0].reset();
                                swal("Send", "{{__(trans('messages.inquiry_success'))}}", "success");
                            }else{
                                $('#contactBtn').val('Send');
                                swal("Error", "{{__(trans('messages.registration_fail'))}}", "error");
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
