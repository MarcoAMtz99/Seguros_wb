<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="overflow-x: hidden;">
    <head>
        <!--VIEWPORT-->
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Seguros') }}</title>
        <!--JQUERY.JS-->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

        <!--POPPER.JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
        <!--BOOTSTRAP.CSS-->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
       <!--  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous"> -->

        
        <!--FONT-AWESOME.CSS-->
        <script src="https://kit.fontawesome.com/f7878fc8d0.js"></script>
        <!--Jquery UI -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <!--STYLE-->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <style>
            html {
              min-height: 100%;
              position: relative;
            }
            body {
              margin: 0;
              margin-bottom: 140px;
            }
            .contenido{
                padding: 0px;
                margin: 0px;
                background-image: url({{ asset('img/fondo_1.jpg') }});
                height: 110%;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            .botonF1, .botonF1:hover{
                width:120px;
                height:40px;
                border-radius:20px;
                background:#25D336;
                right:0;
                bottom:0;
                position:absolute;
                margin-right:16px;
                margin-bottom:16px;
                border:none;
                outline:none;
                color:#FFF;
                font-size:18px;
                box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
                transition:.3s;  
                position: fixed;
                z-index: 2;
                display: block;
                padding-top: 5px;
                text-align: center;
                text-decoration: none;
            }
            .flotante {
                display:scroll;
                position:fixed;
                bottom:320px;
                right:0px;
            }
            .footer {
              position: absolute;
              bottom: 0;
              width: 100%;
              margin: 0;
              height: 140px;
            }
             /*FAQS*/
        
        .faq_question {
            margin: 0px;
            padding: 0px 0px 5px 0px;
            display: inline-block;
            cursor: pointer;
            font-weight: bold;
        }
        
        .faq_answer_container {
            display: none;
        }

        </style>
    </head>
    <body>
        <!--HEADER-->
        {{-- <div class="row p-1 m-0">
            <div class="col-11 m-0 p-0">
                <div class="row ">
                    <div class="col-12 col-sm-6">
                        <h5>
                            AutoSeguroDirecto.com
                        </h5>
                    </div>
                    <div class="col-12 col-sm-6">
                        <p class="text-muted text-justify-left">"A un click de tu Seguro"</p>
                    </div>
                </div>
            </div>
            <div class="col-1 text-right m-0 p-0" pr-2>
                <i class="fa fa-facebook-official" style="font-size:24px;color:#3B569D"></i>
                <i class="fa fa-twitter" style="font-size:24px;color:#1DA1F2"></i>
            </div>
        </div> --}}

        <!--NAV-->
        <div class="row bg-info">
            <div class="col-12">
                <nav class="nav navbar navbar-expand-lg bg-info p-0 m-0">
                    <a class="navbar-brand m-0 p-0" href="{{ route('index') }}"  style="color: white;">
                        <h5 class="d-flex justify-content-around">
                            <img src="{{ asset('img/logo2.jpg') }}" alt="">
                            <p class="mt-3">
                                Autosegurodirecto.com.mx
                            </p>
                            <span class="d-none d-sm-block text-secondary ml-2 mt-3 p-auto">
                                <small>
                                    "A un click de tu Seguro"
                                </small>
                            </span>
                        </h5>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-align-justify text-white"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" style="color: white;" href="{{ url('/') }}">Cotiza</a>
                            </li>
                         <!--    <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="{{ url('/') }}">Inicio</a>
                            </li> -->
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="{{ url('/acerca_nosotros') }}">Acerca de Nosotros</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="{{ url('/preguntas') }}">Preguntas Frecuentes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="{{ url('/contacto') }}">Contacto</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="{{ url('/noticias') }}">Noticias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="{{ url('/aviso') }}">Aviso de privacidad</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="{{ url('/terminos') }}">Terminos y condiciones</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-1 col-md-1 text-right m-auto p-auto d-flex justify-content-around">
                        <a href="https://www.facebook.com/autosegurodirecto" target="_blank">
                            <i class="fab fa-facebook-square" style="font-size:24px;color:white"></i>
                        </a>
                        <a href="https://twitter.com/autosegurodirecto" target="_blank">
                            <i class="fab fa-twitter-square" style="font-size:24px;color:#1DA1F2"></i>
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        <!--CONTENIDO-->
        <div id="app" class="contenido p-0 m-0"> 
            @yield('content')
        </div>

        <!--FOOTER-->
        <div class="row bg-info text-white p-4 footer">
            <a class="botonF1" href="https://wa.me/525611763726?text=Hola%20Autosegurodirecto.com.%20Estoy%20interesado%20en%20contratar%20un%20seguro%20con%20ustedes.">
              <i class="fab fa-whatsapp"></i><span> Whatsapp</span>
            </a>
            <div class="col-12 col-sm-4">
                <p class="font-weight-bold text-center">Autosegurodirecto.com.mx</p>
                <p class="text-center">"A un click de tu Seguro"</p>
            </div>
            <div class="col-12 col-sm-4">
                <p class="font-weight-bold text-center">
                    <i class="fa fa-clock-o" style="font-size:36px"></i>
                    Horario de Atención:
                </p>
                <p class="text-center">Lun–Vie: 10am–6pm</p>
            </div>
            <div class="col-12 col-sm-4">
                <p class="font-weight-bold text-center">¿NECESITA ASISTENCIA?</p>
                <p class="text-center">
                    <i class="fa fa-phone"  style="font-size:36px"></i>
                    TEL DF: 55-6275-8686
                </p>
                
                <p class="text-center">
                    {{-- <a class="text-white" href="https://wa.me/525611763726?text=Hola%20AutoSeguroDirecto.com.%20Estoy%20interesado%20en%20contratar%20un%20seguro%20con%20ustedes.">
                        <i class="fab fa-whatsapp" style="font-size:36px"></i>
                        WHATSAPP: 56-1176-3726
                    </a> --}}
                </p>
            </div>
        </div>

    </body>
                <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.faq_question').click(function() {

                if ($(this).parent().is('.open')) {
                    $(this).closest('.faq').find('.faq_answer_container').slideUp();
                    $(this).closest('.faq').removeClass('open');
                } else {
                    $('.faq_answer_container').slideUp();
                    $('.faq').removeClass('open');
                    $(this).closest('.faq').find('.faq_answer_container').slideDown();
                    $(this).closest('.faq').addClass('open');
                }

            });
        });
    </script>
    <!--BOOTSTRAP.JS-->
        <!-- <script src="{{ asset('js/app.js') }}"></script> -->
        <!-- <script src="{{asset('js/app.js?v=1.1')}}"></script> -->
         <script src="{{mix('js/app.js')}}"></script>
        <script src="{{ asset('js/modernizr-custom.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script>
            $('.botonF1').hover(function(){
              $('.btn').addClass('animacionVer');
            })
            $('.contenedor').mouseleave(function(){
              $('.btn').removeClass('animacionVer');
            })
        </script>

    @yield('scripts')
</html>