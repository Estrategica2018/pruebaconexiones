@extends('layouts.app_side')
@section('content')
@include('layouts/float_buttons')
 <!-- Demo styles -->
 <style>
    

    .swiper-container {
      width: 677px;
      height: 350px;
    }

    .swiper-slide {
      text-align: center;
      font-size: 18px; 
      background-size:675px 350px;
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

    @media(max-width:1161px) and (max-width:1161px) {
      .div-name {
         padding-left: 15px!important; 
         max-width: 100%;
         flex: 0 0 100%;
      } 
    }   

    @media(max-width:768px) {
      .swiper-container {
         width: 562px;
         height: 350px;
      }

      .swiper-slide {  
         background-size:562px 350px; 
      }
    }    


    @media(max-width:618px) {
      .swiper-container {
         width: 481px;
         height: 292px;
      }

      .swiper-slide {  
         background-size: 481px 292px; 
      }
    }

  </style>
<div ng-controller="kitsElementsCtrl" ng-init="getKit()">

   <div ng-show="errorMessageFilter" id="errorMessageFilter"
      class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
      <span class="col">@{{ errorMessageFilter }}</span>
      <span class="col-auto"><a ng-click="errorMessageFilter = null"><i class="far fa-times-circle"></i></a></span>
   </div>

    <div id="main-card" class="mb-3 card w-100">
      <div class="card-body"> 
         <div class="no-gutters row">
            <div class="d-none-result d-none row w-100">
               <div class="p-3">
               @if(isset($files))
                  <div class="swiper-container">
                     <div class="swiper-wrapper kit" ng-class="{'disabled':kit.quantity === 0}">
                     @foreach($files as $file)
                        <div class="swiper-slide" style="background-image:url(/{{ $directory }}/{{$file}});"></div> 
                     @endforeach
                     </div>
                     <div class="d-none kit-imagen-sold-out">
                        Agotado
                     </div>
                     <!-- Add Arrows -->
                     <div class="swiper-button-next" style="color: #007aff;"></div>
                     <div class="swiper-button-prev" style="color: #007aff;"></div>
                  </div>
               @endif
               </div>
               <div class="pl-lg-6 col-12 col-lg-4 div-name ">
                  <h5 class="boder-header p-1 mt-4 mb-3 pl-3">
                  @{{kit.name}}
                  </h5>
                  @{{kit.description}} 
                  <div class="col-12 pl-0 mt-3" >
                     <button ng-click="onAddShoppingCart(kit)" 
                        ng-class="{'disabled':kit.quantity === 0}"
                        class="mt-3 btn btn-sm btn-outline-primary fs-0" href="#" class="col-6">
                        <i class="fas fa-shopping-cart"></i> Comprar
                     </button>
                  </div>
               </div>
               <div class="col-12 mt-4 mt-3" ng-show="listSequence.length > 0">
                  <h5 class="fs-1 pl-3 boder-header">Guías de aprendizaje que te pueden interesar</h5>
                  <div class="row  mt-4">
                     <div class="col-xl-4 col-lg-6 col-md-8" ng-repeat="sequence in listSequence" style="border: 6px solid white;">
                        <div class="card-body bg-light mini-card p-1 row" style="min-width: 376px;">
                           <div class="col-auto">
                              <img class="mt-3 p-0" ng-src="/@{{sequence.url_image}}" style="width:92px;height:auto;">
                           </div>
                           <div class="col-8" style="min-height:210px">
                              <div class="mt-3 kit-description">
                                 <h6 class="boder-header pl-3 fs-0 text-left ">
                                    @{{sequence.name}}
                                 </h6>
                                 @{{sequence.description}}
                              </div>
                              <div class="p-0 text-aling-left" style="position: absolute;bottom: 5px;">
                                 <a class="ml-auto mr-auto mt-1 btn btn-outline-primary fs--1"  ng-href="/guia_de_aprendizaje/@{{sequence.id}}/@{{sequence.name_url_value}}" >Detalle</a>
                                 <a class="pl-3 mt-1 btn btn-outline-primary fs--1" href="#" ng-click="onSequenceBuy(sequence)"  class="col-6">
                                    <i class="fas fa-shopping-cart"></i> Comprar
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div id="loading" class="z-index-10 position-absolute card card-body p-10 w-100 text-align" ng-hide="kit"
      style="min-height: 23vw;">
      cargando...
   </div>
  
</div> 

<script src="{{ asset('/falcon/js/swiper.min.js') }}" defer></script>
<script src="{{ asset('/../angular/controller/kitsElementsCtrl.js') }}" defer></script>
@endsection