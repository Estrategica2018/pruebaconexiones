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

    @media(max-width:768px) {
      .swiper-container {
         width: 562px;
         height: 350px;
      }

      .swiper-slide {  
         background-size:562px 350px; 
      }
    }

  </style>
<div ng-controller="kitsElementsCtrl" ng-init="getKits()">

   <div ng-show="errorMessageFilter" id="errorMessageFilter"
      class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
      <span class="col">@{{ errorMessageFilter }}</span>
      <span class="col-auto"><a ng-click="errorMessageFilter = null"><i class="far fa-times-circle"></a></i></span>
   </div>
  
    <div class="mb-3 card w-100" style="min-width:400px; min-height:400px">
      <div class="card-body"> 
         <div class="no-gutters row">
            <div class="d-none-result d-none row w-100">
               <div class="p-3">
               @if(isset($files))
                  <div class="swiper-container">
                     <div class="swiper-wrapper">
                     @foreach($files as $file)
                        <div class="swiper-slide" style="background-image:url(/{{ $directory }}/{{$file}});"></div> 
                     @endforeach   
                     </div>
                     <!-- Add Arrows -->
                     <div class="swiper-button-next" style="color: #007aff;"></div>
                     <div class="swiper-button-prev" style="color: #007aff;"></div>
                  </div>
               @endif
               </div>
               <div class="pr-0 col-12 col-lg-4">
                  <h5 class="boder-header p-1 mt-4 mb-3 pl-3">
                  @{{kit.name}}
                  </h5>
                  @{{kit.description}} 
                  <div class="col-12 " >
                     <button ng-click="onAddShoppingCart(kit)" ng-disabled="kit.status === 'sold-out' || kit.status === 'no-available'" class="ml-3 mt-3 btn btn-sm btn-outline-primary fs-0" href="#" class="col-6"><i class="fas fa-shopping-cart"></i> Comprar</button>
                  </div>
               </div>
               <div class="col-12 mt-4 mt-md-3" ng-show="listSequence.length > 0">
                  <h5 class="p-1 fs-1 pl-2 boder-header">Guías de aprendizaje que te pueden interesar</h5>
                  <div class="row  mt-4">
                     <div class="col-lg-4 col-md-6" ng-repeat="sequence in listSequence" style="border: 6px solid white;">
                        <div class="card-body bg-light p-1 row">
                           <div class="col-auto">
                              <img class="col-12 mt-3 p-0" ng-src="/@{{sequence.url_image}}" style="width:92px;height:auto;">
                           </div>
                           <div class="col-8">
                              <div class="col-12 mt-3 kit-description">
                                 <h6 class="boder-header p-1  fs-0 text-left ">
                                    @{{sequence.name}}
                                 </h6>
                                 @{{sequence.description}}
                              </div>
                              <div class="col-12 p-0 mt-3 text-aling-left">
                                 <a class="ml-auto mr-auto mt-1 btn btn-outline-primary fs--2" ng-href="../../kit_de_laboratorio/@{{kit_element.id}}/@{{kit_element.name_url_value}}">Detalle</a>
                                 <a class="pl-3 mt-1 btn btn-outline-primary fs--2" href="#" class="col-6">Agregar</a>
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
</div> 

<script src="{{ asset('/falcon/js/swiper.min.js') }}" defer></script>
<script src="{{ asset('/../angular/controller/kitsElementsCtrl.js') }}" defer></script>
@endsection