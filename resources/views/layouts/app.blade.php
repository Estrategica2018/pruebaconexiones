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
    <!-- Add icon library -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />

    <link rel="stylesheet" type="text/css" href="{{ asset('falcon/css/falcon.css') }}">
    <!--link href="{{ asset('falcon/css/theme.css') }}" type="text/css" rel="stylesheet" class="theme-stylesheet"-->
	<link type="text/css" rel="stylesheet" class="theme-stylesheet" href="https://res.cloudinary.com/dfxkgtknu/raw/upload/v1612842034/samples/conexiones/theme.min_cyfngb.css">
	
    <!-- select2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js" integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g==" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.2/angular.min.js" integrity="sha512-7oYXeK0OxTFxndh0erL8FsjGvrl2VMDor6fVqzlLGfwOQQqTbYsGPv4ZZ15QHfSk80doyaM0ZJdvkyDcVO7KFA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-animate/1.8.2/angular-animate.min.js" integrity="sha512-jZoujmRqSbKvkVDG+hf84/X11/j5TVxwBrcQSKp1W+A/fMxmYzOAVw+YaOf3tWzG/SjEAbam7KqHMORlsdF/eA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-sanitize/1.8.2/angular-sanitize.min.js" integrity="sha512-JkCv2gG5E746DSy2JQlYUJUcw9mT0vyre2KxE2ZuDjNfqG90Bi7GhcHUjLQ2VIAF1QVsY5JMwA1+bjjU5Omabw==" crossorigin="anonymous"></script>

    <!-- load ngmessages -->
    <script src="{{ asset('js/ngMessages.js') }}"></script>
    <script src="{{ asset('js/Cubexy.js') }}" type="text/javascript"></script>
    
    <!-- sweetalert2 JS -->
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    
    <!-- Link Swiper's CSS -->
    <!--link rel="stylesheet" href="{{ asset('falcon/css/swiper.min.css') }}"-->
    <!--script src="{{ asset('/falcon/js/swiper.min.js') }}" defer></script-->
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/swipejs/2.2.18/style.min.css" integrity="sha512-+0rLxf9GysTpzDehJJr15kUn2zvyF/Sl3HH+/YBvloZbUUIq7zP6YHInKsRTmF82Bezez0O68DvNqCr8WopvXg==" crossorigin="anonymous" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/swipejs/2.2.18/swipe.min.js" integrity="sha512-N1p/ou8WbK7r0EYPz1g8MWGVvdn3UihwAnCseYFMlNL3o96uJoLNIdKNeZknV3Aiy9+crKmV+aiAZFRrDHsmnQ==" crossorigin="anonymous"></script>
   
   <!-- Moment JS -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
   
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/js/all.min.js" integrity="sha512-UwcC/iaz5ziHX7V6LjSKaXgCuRRqbTp1QHpbOJ4l1nw2/boCfZ2KlFIqBUA/uRVF0onbREnY9do8rM/uT/ilqw==" crossorigin="anonymous"></script>
    <script src="{{ asset('angular/controller/frequentQuestionsCtrl.js')}}"></script>
    @yield('js')
    
</body>

</html>