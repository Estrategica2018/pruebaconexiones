@extends('layouts.app')
@section('content')
<div ng-controller="kitsElementsCtrl" ng-init="allKits()">

   <div ng-show="errorMessageFilter" id="errorMessageFilter"
      class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
      <span class="col">@{{ errorMessageFilter }}</span>
      <span class="col-auto"><a ng-click="errorMessageFilter = null"><i class="far fa-times-circle"></a></i></span>
   </div>

   <div class="mb-3 card">
      <div class="card-body">
         <div class="no-gutters row">
            <div class="mb-3 col-12">
               <div class="justify-content-center justify-content-sm-between row">
                  <div class="text-center col-sm-auto card-header boder-header p-2 ml-3">
                     <h5 class="d-inline-block">Elementos de laboratorio</h5>
                  </div>
                  <div
                     class="d-none-result d-none d-flex flex-center fs--1 mt-1 mt-sm-0 col-sm-auto d-none-result d-none">
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
                  <div class="card-body bg-light text-center p-4 row">
                     <img class="kit-imagen col-12 p-0" ng-src="@{{kit.url_image}}" width="62px" height="62px" />
                     <div class="col-12 mt-3 kit-description" id="sequence-description-@{{kit.id}}">
                        <h6 class="boder-header p-1  fs-1">
                           @{{kit.name}}
                        </h6>
                        @{{kit.description}}
                     </div>
                     <div class="col-12 mt-3" style="text-align: left;">
                        <a ng-show="kit.type==='kit'" class="ml-auto mr-auto mt-1 btn btn-outline-primary fs--2" ng-href="/kit_de_laboratorio/@{{kit.id}}/@{{kit.name.replace(' ','_',true)}}">Detalle</a>
						<a ng-show="kit.type==='element'" class="ml-auto mr-auto mt-1 btn btn-outline-primary fs--2" ng-href="/elemento_de_laboratorio/@{{kit.id}}/@{{kit.name.replace(' ','_')}}">Detalle</a>
                        <a class="pl-3 ml-4 mt-1 btn btn-outline-primary fs--2" href="#" class="col-6">Comprar</a>
                     </div>
                  </div>
               </div>
            </div>

            <div class="p-3 border-lg-y col-lg-2 w-100"
               style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-hide="kits.length > 0">
               cargando...
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