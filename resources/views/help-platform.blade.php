@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div class="container" style="min-width:661px;"  ng-controller="helpPlatformCtrl" ng-init="init()">
   <div class="card card-body p-10 text-align border-lg-y w-100 h-100"
      style="min-height: 23vw;" ng-hide="loadFinished">
      cargando...
   </div>
   <div class="d-none-result d-none background-sequence-card mt-3 ml-3 pt-2 row card card-body" w="1519" h="4124" ng-show="loadFinished">
      
	  
   </div>
</div>
<script src="{{ asset('angular/controller/helpPlatformCtrl.js') }}" defer></script>
@endsection

