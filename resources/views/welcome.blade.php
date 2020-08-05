@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

    <!-- Swiper -->
    <div class="swiper-container mt-lg--2">
       <div class="swiper-wrapper">
          <div class="swiper-slide"
             style="background-image:url(images/sliderCarrucelHome/slide1.jpg?v.1);">
          </div>
          <div class="swiper-slide"
             style="background-image:url(images/sliderCarrucelHome/slide2.jpg?v.1);">
          </div>
          <div class="swiper-slide"
             style="background-image:url(images/sliderCarrucelHome/slide3.jpg?v.1);">
          </div>
          <div class="swiper-slide"
             style="background-image:url(images/sliderCarrucelHome/slide4.jpg?v.1);">
          </div>
       </div>
       <!-- Add Arrows -->
       <div class="swiper-button-next" style="color: #007aff;"></div>
       <div class="swiper-button-prev" style="color: #007aff;"></div>
    </div>
    <!-- Demo styles -->
    <style type="text/css">
       .swiper-slide {
          text-align: center;
          font-size: 18px;
          background: #fff;
          /* Center slide text vertically */
          display: -webkit-box;
          display: -ms-flexbox;
          display: -webkit-flex;
          display: flex;
          -webkit-box-pack: center;
          -ms-flex-pack: center;
          -webkit-justify-content: center;
          justify-content: center;
          -webkit-box-align: center;
          -ms-flex-align: center;
          -webkit-align-items: center;
          align-items: center;
       }
    </style>

    <div class="text-center fs--1 no-gutters row w-100">
       <div class="mb-1 col-xxl-1 col-md-4 col-lg-1 bg-white"></div>
       <div class="mb-1 col-6 col-md-4 col-lg-2">
          <div class="thumbnail bg-white p-3 h-100">
             <a href="{{route('pedagogy')}}">
                <img class="welcome-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
                   src="{{ asset('images/iconosParteInferiorHome/enfoque_Mesa de trabajo 1.png') }}" alt="">
             </a>
             <h6 class="mb-1 font-weight-bold">
                <a href="{{route('pedagogy')}}">Enfoque pedagógico</a>
             </h6>
             <p class="fs--2 mb-1 ">
                <div class="fs--1 text-aling">
                   Se basa en el diseño de experiencias de aprendizaje que generan <strong>Conexiones</strong> entre teoría y práctica, orientadas al desarrollo de pensamiento científico, a través de la indagación 
                   <a href="{{route('pedagogy')}}" class="font-weight-bold"> (Ver+).</a>
                 </div>
             </p>
          </div>
       </div>
       <div class="mb-1 col-xxl-2 col-6 col-md-4 col-lg-2">
          <div class="bg-white p-3 h-100">
             <a href="{{route('sequences.search')}}">
                <img class="welcome-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
                   src="{{ asset('images/iconosParteInferiorHome/guiasprendizaje_Mesa de trabajo 1.png') }}" width="100" alt=""></a>
             <h6 class="mb-1 font-weight-bold">
               <a href="{{route('sequences.search')}}">Guías de aprendizaje</a>
             </h6>
             <p class="fs--2 mb-1">
                <div class="fs--1 text-aling">
                   <strong>Conexiones</strong> cuenta  con una completa serie de guías para el aprendizaje de las ciencias naturales, en formato multimedia que permite la interactividad
                   <a href="{{route('sequences.search')}}" class="font-weight-bold"> (Ver+).</a>
                </div>
             </p>
          </div>
       </div>
       <div class="mb-1 col-xxl-2 col-6 col-md-4 col-lg-2">
          <div class="bg-white p-3 h-100">
             <a href="{{route('elementsKits.search')}}">
                <img class="welcome-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
                   src="{{ asset('images/iconosParteInferiorHome/labs_Mesa de trabajo 1.png') }}" width="100" alt=""></a>
             <h6 class="mb-1 font-weight-bold"><a href="{{route('elementsKits.search')}}">Implementos de laboratorio</a></h6>
             <p class="fs--2 mb-1">
                <div class="fs--1 text-aling">
                   Ofrecemos implementos de laboratorio para la realización de las prácticas experimentales propuestas por
                   <strong> Conexiones </strong>,
                   y de otras que surjan de la indagación científica 
                   <a href="{{route('elementsKits.search')}}" class="font-weight-bold"> (Ver+).</a>
                </div>
             </p>
          </div>
       </div>
       <div class="mb-1 col-xxl-2 col-6 col-md-4 col-lg-2">
          <div class="bg-white p-3 h-100">
             <a href="{{route('ratingPlan.list')}}">
                <img class="welcome-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
                   src="{{ asset('images/iconosParteInferiorHome/planes_Mesa de trabajo 1.png') }}" width="100" alt=""></a>
             </a>
             <h6 class="mb-1 font-weight-bold"><a href="{{route('ratingPlan.list')}}">Planes de acceso</a></h6>
             <p class="fs--2 mb-1">
                <div class="fs--1 text-aling">
                   Tenemos diferentes planes para acceder a los contenidos educativos de <strong>Conexiones</strong>, de manera que pueden elegir guías de aprendizaje completas, o seleccionar las partes de estas que les interesan 
                   <a href="{{route('ratingPlan.list')}}" class="font-weight-bold"> (Ver+).</a>
                </div>
             </p>
          </div>
       </div>
       <div class="mb-1 col-xxl-2 col-6 col-md-4 col-lg-2">
          <div class="bg-white p-3 h-100">
             <a href="#" onclick="onRatingPlanFree()">
                <img class="welcome-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
                   src="{{ asset('images/iconosParteInferiorHome/gratisPrueba_Mesa de trabajo 1.png') }}" width="100" alt=""></a>
             </a>
             <h6 class="mb-1 font-weight-bold"><a href="#" onclick="onRatingPlanFree()">Prueba Gratuita</a></h6>
             <div class="fs--1 text-aling">
                Ponemos a disposición el acceso a la plataforma <strong>Conexiones</strong> por 15 días, para que conozcan la propuesta educativa y las posibilidades de aprendizaje que pueden tener con esta 
                <a href="#" onclick="onRatingPlanFree()" class="font-weight-bold"> (Ver+).</a>
             </div>
          </div>
       </div>
       <div class="mb-1 col-xxl-1 col-6 col-md-4 col-lg-1 bg-white"></div>
    </div>



<script>

   window.onload = function() {
         var swiper = new Swiper('.swiper-container', {
             navigation: {
             nextEl: '.swiper-button-next',
             prevEl: '.swiper-button-prev',
            },
         });
   };

   $( window ).resize(function() {
       resizable();
    });

   function resizable() {
      var height = $( window ).width() * 250 / 1291;
      $('.swiper-slide').css('height',height);
      $('.swiper-slide').css('background-size','100% '+height+'px');
   }

   resizable();

   function onRatingPlanFree(ratingPlanId) {
        var ratingPlanId = '{{$rating_plan_id_free}}}';
        swal({
          text: "Confirma para acceder a nuestra prueba gratuita",
          type: "warning",
          showConfirmButton: true,showCancelButton: true
        })
        .then((willConfirm) => {
          if (willConfirm) {
            swal({text:'Serás redireccionado al registro',showConfirmButton: false,showCancelButton: false});
            window.location='/validate_registry_free_plan/'+ratingPlanId;
          }
        });
    };
</script>

@endsection