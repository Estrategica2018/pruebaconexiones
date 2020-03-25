@extends('layouts.app')
@section('content')
<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="{{ asset('falcon/css/swiper.min.css') }}">

<!-- Swiper -->
<div class="swiper-container">
   <div class="swiper-wrapper">
      <div class="swiper-slide"
         style="background-image:url(images/welcome/swiper-container/swiper-container-1.png); background-size: 92vw 50vw;">
      </div>
      <div class="swiper-slide"
         style="background-image:url(images/welcome/swiper-container/swiper-container-2.png); background-size: 92vw 50vw;">
      </div>
      <div class="swiper-slide"
         style="background-image:url(images/welcome/swiper-container/swiper-container-3.png); background-size: 92vw 50vw;">
      </div>
      <div class="swiper-slide"
         style="background-image:url(images/welcome/swiper-container/swiper-container-4.png); background-size: 92vw 50vw;">
      </div>
      <div class="swiper-slide"
         style="background-image:url(images/welcome/swiper-container/swiper-container-5.png); background-size: 92vw 50vw;">
      </div>
   </div>
   <!-- Add Arrows -->
   <div class="swiper-button-next" style="color: white;"></div>
   <div class="swiper-button-prev" style="color: white;"></div>
</div>
<!-- Demo styles -->
<style type="text/css">
   .swiper-container {
      width: 100%;
      height: 100%;
   }

   .swiper-slide {
      text-align: center;
      height: 50vw;
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

   .img-thumbnail {
      height: 79px;
   }
</style>

<div class="text-center fs--1 no-gutters row">
   <div class="mb-1 col-xxl-1 col-0 col-md-0 col-lg-1 bg-white"></div>
   <div class="mb-1 col-xxl-2 col-6 col-md-4 col-lg-2">
      <div class="thumbnail bg-white p-3 h-100">
         <a href="/pages/profile">
            <img class="img-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
               src="{{ asset('images/welcome/H6 menú.png') }}" alt="">
         </a>
         <h6 class="mb-1">
            <a href="/pages/profile">Enfoque pedagógico</a>
         </h6>
         <p class="fs--2 mb-1">
            <a class="text-700" href="/pages/people#!">
               Nuestra propuesta educativa se basa en el diseño de experiencias de aprendizaje
               que generan <strong class="primary">Conexiones </strong> entre teoría y práctica, orientadas a favorecen
               el desarrollo de
               pensamiento científico de niñas, niños y jóvenes, a través de la indagación <a href="#"> (Ver +).</a>
         </p>
      </div>
   </div>
   <div class="mb-1 col-xxl-2 col-6 col-md-4 col-lg-2">
      <div class="bg-white p-3 h-100">
         <a href="/pages/profile">
            <img class="img-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
               src="{{ asset('images/welcome/H7 menú.png') }}" width="100" alt=""></a>
         <h6 class="mb-1"><a href="{{route('sequences.search')}}">Guías de aprendizaje</a></h6>
         <p class="fs--2 mb-1">
            <a class="text-700" href="/pages/people#!">
               Contamos con una completa serie de guías para el aprendizaje de las ciencias naturales en formato
               multimedia,
               disponibles en el portal educativo <strong>Conexiones</strong> <a href="#"> (Ver +).</a>
            </a>
         </p>
      </div>
   </div>
   <div class="mb-1 col-xxl-2 col-6 col-md-4 col-lg-2">
      <div class="bg-white p-3 h-100">
         <a href="/pages/profile">
            <img class="img-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
               src="{{ asset('images/welcome/H8 menú.jpg') }}" width="100" alt=""></a>
         <h6 class="mb-1"><a href="{{route('elementsKits.search')}}">Kits de laboratorio</a></h6>
         <p class="fs--2 mb-1">
            <a class="text-700" href="/pages/people#!">
               Ofrecemos implementos de laboratorio para la realización de las prácticas experimentales propuestas por
               <strong> Conexiones </strong>,
               y de otras que surjan de la indagación científica <a href="#"> (Ver +).</a>
            </a>
         </p>
      </div>
   </div>
   <div class="mb-1 col-xxl-2 col-6 col-md-4 col-lg-2">
      <div class="bg-white p-3 h-100">
         <a href="/pages/profile">
            <img class="img-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
               src="{{ asset('images/welcome/H9 menú.jpg') }}" width="100" alt=""></a>
         <h6 class="mb-1"><a href="/pages/profile">Planes de acceso</a></h6>
         <p class="fs--2 mb-1">
            <a class="text-700" href="/pages/people#!">
               Tenemos diferentes planes de acceso a los contenidos educativos de <strong> Conexiones </strong>, 
               de manera que  se puede elegir tener acceso a las guías de aprendizaje completas, o seleccionar las partes de estas que les interesan <a href="#"> (Ver +).</a>
            </a>
         </p>
      </div>
   </div>
   <div class="mb-1 col-xxl-2 col-6 col-md-4 col-lg-2">
      <div class="bg-white p-3 h-100">
         <a href="/pages/profile">
            <img class="img-thumbnail img-fluid rounded-circle mb-3 shadow-sm"
               src="{{ asset('images/welcome/caratula 14.png') }}" width="100" alt=""></a>
         <h6 class="mb-1"><a href="/pages/profile">Prueba Gratuita</a></h6>
         <p class="fs--2 mb-1">
            <a class="text-700" href="/pages/people#!">Elige el plan de acceso que
               Ponemos a disposición el acceso a la plataforma Conexiones por 15 días, para que conozcan la propuesta educativa y las posibilidades de aprendizaje que pueden tener con esta <a href="#"> (Ver +).</a>
            </a>
         </p>
      </div>
   </div>
   <div class="mb-1 col-xxl-1 col-6 col-md-4 col-lg-1 bg-white"></div>
</div>

<script src="{{ asset('/falcon/js/swiper.min.js') }}" defer></script>

<script>
   window.onload = function() {
         var swiper = new Swiper('.swiper-container', {
         navigation: {
         nextEl: '.swiper-button-next',
         prevEl: '.swiper-button-prev',
         },
         });
   };
</script>

@endsection