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
    <link rel="shortcut icon" href="https://falcon.technext.it/favicon.ico">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#2c7be5">
    <link rel="stylesheet" type="text/css" href="{{ asset('falcon/css/falcon.css') }}">
    <!-- Add icon library -->
    <link rel="stylesheet" href="{{ asset('font-awesome/v5.12.1/css/all.min.css') }}">

    <link href="{{ asset('falcon/css/theme.css') }}" type="text/css" rel="stylesheet" class="theme-stylesheet">
	<!-- select2 CSS -->
	<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
	
    <script src="{{ asset('js/jquery-3.3.1.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/angular.1.6.4.js') }}" type="text/javascript"></script>
    <!-- load ngmessages -->
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.3/angular-messages.js"></script>
    <script src="{{ asset('js/Cubexy.js') }}" type="text/javascript"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


</head>
<body>
    <div id="app" ng-app="MyApp">
        <main class="main" id="main">
            <div class="container">
			   
			   @include('layouts/sidebar')
			    
               <div class="content">
                  
				  @include('layouts/navbar')
				  
                  @yield('content')
				  
                  <footer>
					@include('layouts/footer')
                  </footer>
               </div>
            </div>
         </main>
    </div>
    <script src="{{asset('/../angular/app.js')}}"></script>
	<script src="{{ asset('/../angular/controller/NavBarController.js') }}" defer></script>
    <script src="{{ asset('/../angular/controller/ShoppingCardController.js') }}" defer></script>
    <script src="{{ asset('font-awesome/v5.12.1/js/all.min.js') }}" type="text/javascript"></script>
    @yield('js')
</body>
</html>
