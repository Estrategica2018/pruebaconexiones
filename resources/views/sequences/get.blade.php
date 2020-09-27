@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<!-- Link Swiper's CSS -->
<link rel="stylesheet" href="{{ asset('falcon/css/swiper.min.css') }}">

<style>
[aria-labelledby="swal2-title"] {
    padding: 50px!important;
}
</style>
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
</style>

<div ng-controller="sequencesGetCtrl" ng-init="init()" class="w-100">
   <div ng-show="errorMessageFilter" id="errorMessageFilter"
      class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">

      <span class="col">@{{ errorMessageFilter }}</span>
      <span class="col-auto"><a ng-click="errorMessageFilter = null"><i class="far fa-times-circle"></a></i></span>
   </div>
   <div class="mb-3 card" >
      <div class="card-body">
         <div class="no-gutters row">
            <div class="d-none-result2 d-none row w-100 mt-3">
                 <div class="pr-0 col-12 sequence-description ml-2" id="sequence-description-@{{sequence.id}}">
                    <h5 class=" boder-header pl-3">
                       @{{sequence.name}}
                    </h5>
                 </div>
                 <div class="col-12 row mt-2">
                   <div class="col-sm-7 m-auto col-md-4 col-lg-3 text-center">
                        <img src="/@{{sequence.url_image}}" width="70%" height="auto" class="mr-auto ml-auto">
                   </div>
                   <div class="col-sm-7  ml-auto mr-auto col-md-4  col-lg-5 mt-3 mt-md-0">
                          <iframe src="sequence.url_vimeo" frameborder="0" width="100%" height="100%" refreshable="sequence.url_vimeo"
                          allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                   </div>
                   <div class="col-12 mt-3 mt-md-0 col-md-4  col-lg-4">
                     <strong>Punto de encuentro: </strong>@{{sequence.description}}
                     <p>
                        <a ng-click="showMash(sequence)" class="ml-3 mt-3 btn btn-outline-primary fs--1" href="#" class="col-6">
                            <i class="fas fa-search"></i> Ver detalle
                        </a>                     
                        <a ng-click="onSequenceBuy(sequence)" class="ml-3 mt-3 btn btn-outline-primary fs--1" href="#" class="col-6">
                            <i class="fas fa-shopping-cart"></i> Comprar
                        </a>
                     </p>
                   </div>
                 </div>
                 
                 <div class="col-12 ml-2 mt-3" ng-show="elementsKits.length > 0">
                   <h5 class="pl-3 mt-4 mb-3 boder-header">Esta guía requiere instrumentos y materiales de laboratorio </h5>
                   <div class="row">
                       <div class="col-xl-4 col-md-6" ng-repeat="kit_element in elementsKits" style="border: 6px solid white;">
                          <div class="card-body bg-light text-center p-1 row">
                             <div class="col-6">
                                <img class="p-0" ng-src="/@{{kit_element.url_image}}" width="100%" height="auto" style="margin-top: 17px;"/>
                            </div>
                             <div class="col-6">
                                 <div class="mt-3 kit-description" id="sequence-description-@{{kit_element.id}}">
                                    <h6 class="boder-header p-1 text-left fs-0">
                                       @{{kit_element.name}}
                                    </h6>
                                    @{{kit_element.description}}
                                 </div>
                                 <div class="p-0 mt-3 text-aling-left">
                                    <a ng-show="kit_element.type==='kit'" class="ml-auto mr-auto mt-1 btn btn-outline-primary fs--2" ng-href="../../kit_de_laboratorio/@{{kit_element.id}}/@{{kit_element.name_url_value}}">Detalle</a>
                                    <a ng-show="kit_element.type==='element'" class="ml-auto mr-auto mt-1 btn btn-outline-primary fs--2" ng-href="../../elemento_de_laboratorio/@{{kit_element.id}}/@{{kit_element.name_url_value}}">Detalle</a>
                                    <button ng-class="{'disabled': kit_element.quantity === 0}" class="pl-3 mt-1 btn btn-outline-primary fs--2" ng-click="buyKitElement(kit_element)">Agregar</button>
                                    
                                 </div>
                            </div>
                          </div>
                       </div>
                   </div>
                 </div>
            </div>

            <div id="loading" class="p-10 w-100 text-align" ng-hide="sequence"
               style="min-height: 23vw;">
               cargando...
            </div>

         </div>
      </div>
   </div>
</div>

<script src="{{ asset('/falcon/js/swiper.min.js') }}" defer></script>
<script src="{{ asset('/../angular/controller/sequencesGetCtrl.js') }}" defer></script>

@endsection