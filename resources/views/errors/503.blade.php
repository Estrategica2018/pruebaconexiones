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
    

    @yield('plugins')

</head>

<body>
<div id="app" ng-app="MyApp">
    <main class="main" id="main">
        <div class="container">
            <div class="col-12 ml-auto mr-auto">
                <div class="text-center card">
                    <div class="p-5 card-body">
                        <div class="display-1 text-200 fs-error">503</div>
                        <p class="lead mt-4 text-800 text-sans-serif font-weight-semi-bold">Whoops, página web en mantenimiento</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="{{ asset('font-awesome/v5.12.1/js/all.min.js') }}" type="text/javascript"></script>
@yield('js')

</body>

</html>