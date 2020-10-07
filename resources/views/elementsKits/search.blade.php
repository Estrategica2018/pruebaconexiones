@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div ng-controller="kitsElementsCtrl" ng-init="allKits()">

   <div ng-show="errorMessageFilter" id="errorMessageFilter"
      class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
      <span class="col">@{{ errorMessageFilter }}</span>
      <span class="col-auto"><a ng-click="errorMessageFilter = null"><i class="far fa-times-circle"></a></i></span>
   </div>

   <div class="p-10 card card-body w-100 text-align"
      style="min-height: 23vw;" ng-hide="kit_elements">
      cargando...
   </div>
            
   <div class="mb-3 card w-100 d-none-result d-none " ng-show="kit_elements">
      <div class="card-body">
         <div class="no-gutters row">
            <div class="mb-3 col-12">
               <div class="justify-content-center justify-content-sm-between row">
                  <div class="text-center col-sm-auto card-header boder-header p-2 ml-3">
                     <h5 class="d-inline-block">Implementos de laboratorio</h5>
                  </div>
                  <div class="d-flex flex-center fs--1 mt-1 mt-sm-0 col-sm-auto">
                     <input ng-change="onSeachChange()" placeholder="Buscar..." aria-label="Search" type="search"
                        ng-model="searchText" name="searchText" id="searchText" ng-keyup="complete($event, searchText)"
                        ng-blur="fillTextbox($event,searchText)" class="mr-2 rounded-pill search-input form-control"
                        style="font-size: 0.85rem;">
                  </div>
               </div>
            </div>
            <div class="d-none-result d-none row w-100">
               <div class="col-lg-4 col-md-6" ng-repeat="kit in kit_elements | filter: searchText"
                  style="border: 6px solid white;">
                  <div class="card-body bg-light text-center p-4 row h-100">
                     <img ng-class="{'disabled':kit.status === 'sold-out' || kit.status === 'no-available'}" class="kit-imagen col-12 p-0" ng-src="{{asset('/')}}@{{kit.url_image}}" width="62px" height="62px" />
                     <div class="d-none kit-imagen-sold-out">
                        Agotado
                     </div>
                     <div class="col-12 mt-3 kit-description" id="sequence-description-@{{kit.id}}">
                        <h6 ng-class="{'boder-header-green': kit.type==='element'}" class="boder-header p-1  fs-1">
                           @{{kit.name}}
                        </h6>
                        @{{kit.description}}
                     </div>
                     <div class="col-12 mt-auto" style="text-align: left;">
                        <a ng-show="kit.type==='kit'" class="ml-auto mr-auto mt-1 btn btn-outline-primary fs--2" ng-href="/kit_de_laboratorio/@{{kit.id}}/@{{kit.name_url_value}}">Detalle</a>
                        <a ng-show="kit.type==='element'" class="ml-auto mr-auto mt-1 btn btn-outline-primary fs--2" ng-href="/elemento_de_laboratorio/@{{kit.id}}/@{{kit.name_url_value}}">Detalle</a>
                        <button  ng-class="{'disabled':kit.status === 'sold-out' || kit.status === 'no-available'}"
                                 ng-click="onAddShoppingCart(kit)" class="pl-3 ml-4 mt-1 btn btn-outline-primary fs--2">
                                 <i class="fas fa-shopping-cart"></i> Comprar
                        </button>
                     </div>
                  </div> 
               </div>
            </div>
            <div class="d-none-result d-none p-3 border-lg-y col-lg-2 w-100"
               style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-show="searchText.length > 0 && kit_elements.length > 0">
               No se encontraron implementos de laboratorio que coincidan con la búsqueda @{{searchText}}
            </div>
         </div>
      </div>
   </div>
</div>

<script src="{{ asset('/../angular/controller/kitsElementsCtrl.js') }}" defer></script>

<style type="text/css">
   .list-group-item:hover {
      color: #337ab7;
      text-shadow: 0 0 1em #337ab7;
      cursor: pointer;
   }
</style>
@endsection