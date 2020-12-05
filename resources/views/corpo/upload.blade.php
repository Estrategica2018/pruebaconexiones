<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Carga información Corpoboyacá</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Carga información Corpoboyacá</title>
    <link rel="shortcut icon" href="https://www.corpoboyaca.gov.co/compas/wp-content/themes/compas-2019/imgs/logo-corpo.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#2c7be5">
    <link rel="stylesheet" type="text/css" href="{{ asset('falcon/css/falcon.css') }}">
    <!-- Add icon library -->
    <link rel="stylesheet" href="{{ asset('font-awesome/v5.12.1/css/all.min.css') }}">

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

</head>

<body>
    <div id="app" ng-app="MyApp">
        <main class="main" id="main">
            <div class="container">
                
   <div class="content">

      
      <div class="p-lg-4 p-md-3 p-sm-2 sticky-margin-top-ie justify-content-center no-gutters">
         <div class="container">
   <div class="content">
      <div class="row">
         
         <div class="col-md-8">
            <div class="mb-3 card">
               <div class="card-header row no-gutters">
                  <h5 class="mb-0 col">Cargar novedades de trámites</h5>
                  
               </div>
               <div class="bg-light card-body">
                  <div>
                     <form action="{{ route('corpoboyaca_upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group d-flex">
                           <label for="groupLabel" class="" style="width: 282px;">Archivo de carga de trámites</label>
                           <input name="fileInput" id="fileInput" type="file" class="form-control-file">
                        </div>
                        
                        <button type="submit" class="btn btn-outline-primary">
                           <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" class="svg-inline--fa fa-plus fa-w-14 mr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="transform-origin: 0.4375em 0.5em;">
                              <g transform="translate(224 256)">
                                 <g transform="translate(0, 0)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                    <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                 </g>
                              </g>
                           </svg>
                           Cargar archivo
                        </button>
                     </form>
                  </div>
                  @if(isset($result))
                  <div class="mt-3">
                  @foreach($result as $key => $item)
                   <div class="d-flex">
                     <label>{{$key}} : </label>
                     <span>{{$item}}</span>
                   </div>
                  @endforeach
                  </div>
                  @endif
                  
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
      </div>

   </div>  
            </div>
        </main>
    </div>
    
    <script src="{{asset('angular/app.js')}}"></script>
    <script src="{{ asset('font-awesome/v5.12.1/js/all.min.js') }}" type="text/javascript"></script>

    
</body>

</html>

