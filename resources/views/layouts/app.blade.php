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

        
        <!--FONT-AWESOME.CSS-->
        <script src="https://kit.fontawesome.com/f7878fc8d0.js"></script>
        <!--Jquery UI -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <!--STYLE-->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <style>
            .contenido{
                padding: 0px;
                margin: 0px;
               background-image: url({{ asset('img/fondo_1.jpg') }});
               height: 110%;
               background-position: center;
               background-repeat: no-repeat;
               background-size: cover;
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
                                AutoSeguroDirecto.com.mx
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
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="{{ url('/') }}">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="{{ url('/acerca_nosotros') }}">Acerca de Nosotros</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="#">Preguntas Frecuentes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="#">Contacto</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: white;" href="#">Noticias</a>
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
        <div class="row bg-info text-white p-4">
            <div class="col-12 col-sm-4">
                <p class="font-weight-bold text-center">AutoSeguroDirecto.com.mx</p>
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
                    TEL DF: 6275-8686
                </p>
                <p class="text-center">
                    <a class="text-white" href="https://wa.me/525611763726?text=Hola%20AutoSeguroDirecto.com.%20Estoy%20interesado%20en%20contratar%20un%20seguro%20con%20ustedes.">
                        <i class="fab fa-whatsapp" style="font-size:36px"></i>
                        WHATSAPP: 56-1176-3726
                    </a>
                </p>
            </div>
        </div>

    </body>
    <!--BOOTSTRAP.JS-->
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/modernizr-custom.js') }}"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    @yield('scripts')
</html>