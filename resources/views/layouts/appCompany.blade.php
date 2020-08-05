<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!--<script src="{{ asset('js/app.js') }}" defer></script>-->
    <!-- Scripts


     Fonts
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    Styles
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
-->

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title> | Plataforma de aprendizaje</title>
    <link rel="shortcut icon" href="{{ asset('images/icons/educonexiones.ico') }}">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#2c7be5">
    <link rel="stylesheet" type="text/css" href="{{ asset('falcon/css/falcon.css') }}">
    <!-- Add icon library -->
    <link rel="stylesheet" href="{{ asset('font-awesome/4.7.0/css/font-awesome.min.css') }}">

    <link href="{{ asset('falcon/css/theme.css') }}" type="text/css" rel="stylesheet" class="theme-stylesheet">
    <!-- select2 CSS -->
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="{{ asset('falcon/css/swiper.min.css') }}">
    
    <script src="{{ asset('js/jquery-3.3.1.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/angular.1.6.4.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/angular-animate.js') }}" type="text/javascript"></script>
    <!-- load ngmessages -->
    <script src="{{ asset('js/ngMessages.js') }}"></script>

</head>
<body>
    <div id="app" ng-app="MyApp">
        <main class="main" id="main">
            <div class="container">
               <div class="content">
                  @yield('content')
               </div>
            </div>
         </main>
    </div>
    <script src="{{asset('/../angular/app.js')}}"></script>
    <script src="{{ asset('/../angular/controller/NavBarController.js') }}" defer></script>
    <script src="{{ asset('/../angular/controller/ShoppingCartController.js') }}" defer></script>
    @yield('js')
</body>
</html>
