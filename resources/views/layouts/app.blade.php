<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Conexiones | Plataforma de aprendizaje</title>
    <link rel="shortcut icon" href="{{ asset('images/icons/educonexiones.ico') }}">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#2c7be5">
    <link rel="stylesheet" type="text/css" href="{{ asset('falcon/css/falcon.css') }}">
    <!-- Add icon library -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />

    <link href="{{ asset('falcon/css/theme.css') }}" type="text/css" rel="stylesheet" class="theme-stylesheet">
    <!-- select2 CSS -->
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    

    <script src="{{ asset('js/jquery-3.5.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>
    
    <script src="{{ asset('js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/angular.1.8.0.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/angular-animate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/angular-sanitize.min.js') }}" type="text/javascript"></script>	
    <!-- load ngmessages -->
    <script src="{{ asset('js/ngMessages.js') }}"></script>
    <script src="{{ asset('js/Cubexy.js') }}" type="text/javascript"></script>
    
    <!-- sweetalert2 JS -->
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="{{ asset('falcon/css/swiper.min.css') }}">
    <script src="{{ asset('/falcon/js/swiper.min.js') }}" defer></script>
   
   <!-- Moment JS -->
   <script src="{{ asset('js/moment.js') }}" type="text/javascript"></script>
    @yield('plugins')

</head>

<body>
    <div id="app" ng-app="MyApp">
        <main class="main" id="main">
            <div class="container">
                @yield('content_layout')
            </div>
        </main>
    </div>
    
    <script src="{{asset('angular/app.js')}}"></script>
    <script src="{{ asset('angular/controller/NavBarController.js') }}" defer></script>
    <script src="{{ asset('angular/controller/ShoppingCartController.js') }}" defer></script>
    <script src="{{ asset('font-awesome/v5.12.1/js/all.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('angular/controller/frequentQuestionsCtrl.js')}}"></script>
    @yield('js')
    
</body>

</html>