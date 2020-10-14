@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div ng-controller="ratingPlanListCtrl" ng-init="init(1)">
   
    <div ng-show="errorMessageFilter"
        id="errorMessageFilter" 
        class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
         <span class="col">@{{ errorMessageFilter }}</span>
         <span class="col-auto"><a ng-click="errorMessageFilter = null"><i class="far fa-times-circle"></a></i></span>
    </div>

    <div class="mb-3 card">
      <div class="card-body">
         <div class="row">
            <div class="mb-3 col-12">
               <div class="justify-content-center justify-content-sm-between row">
                  <div class="text-center col-sm-auto boder-header p-2 ml-3">
                     <h5 class="d-inline-block">Planes de acceso</h5>
                  </div>
               </div>
            </div>
            <div class="ml-2 pr-lg-5 pr-md-4 pr-sm-2 col-12">
                <p>Es importante tener en cuenta que si bien hay <strong>una ruta de viaje</strong> trazada, en <strong>Conexiones</strong> se puede  elegir hacer el recorrido en el <strong>orden</strong> propuesto o de manera <strong>aleatoria</strong>, pues aunque los <strong>momentos</strong> de cada <strong>guía de aprendizaje</strong> se relacionan entre sí, cada uno puede abordarse independientemente.</p>
                <p>De esta manera, Conexiones tiene una <strong>estructura flexible</strong> que se adapta  a diversos <strong>intereses de aprendizaje</strong>, <strong>planes de estudio</strong> y disponibilidad de <strong>tiempo</strong>,  dando la opción de elegir entre las siguientes <strong>opciones</strong>: </p>
                <ul>
                <li><strong>Guía de aprendizaje completa</strong> con sus <strong>ocho momentos</strong>, cada uno con preguntas exploradoras de saberes, experiencias científicas, explicaciones de ciencias  en contexto e  ideas para + conexiones. </li>
                <li><strong>Uno</strong> o <strong>varios momentos</strong> o etapas de una misma guía de aprendizaje o de  varias.</li>
                <li><strong>Una</strong> o <strong>varias experiencias</strong> científicas, con los videos orientadores para su realización.</li>
                </ul>
                <p>A continuación puede consultar los diferentes <strong>planes disponibles</strong>. Si tiene dudas o sugerencias, <a target="_blank" href="{{route('contactus')}}"> <strong>contáctenos</strong></a> y con gusto le llamaremos para darle más detalles y ofrecerle la mejor opción de acuerdo sus expectativas.</p>
            </div>
            <div class="d-none-result d-none row col-12 ml-auto mr-auto mb-3">
               <div class="mt-xl-0 mt-5 col-xl-1_5 col-md-3 col-sm-4 col-6 pl-0 pr-0 ratinPlanCard" ng-repeat="ratingPlan in ratingPlans" style="border: 10px solid white;">
                  <div class="card-header card-rating-background-id-@{{$index}} mt-3 fs--3 flex-100 box-shadow ">
                     <h5 class="alingTextPlan font-weight-bold text-center fs-0 ratinPlanCard-name"> <span class="ml-2" style="color: white;">@{{ratingPlan.name}} </span></h5>  
                  </div>   
                  <div class="card-body ratinPlanCard-body bg-light pr-0 pl-0 pb-0 w-100 box-shadow " style="min-height: 282px;">
                     <ul class="p-0 ml-2 pr-2 pl-2">
                        <li class="fs-2 small pr-0 mt-4 ml-3 card-rating-plan-id-@{{$parent.$index}}" style="line-height: 17px;    min-height: 70px;" ng-repeat="item in ratingPlan.description_items">
                           <span class="color-gray-dark font-14px font-family ">@{{item}}</span>
                        </li>
                     </ul>
                     <div ng-hide="ratingPlan.is_free" class="card-footer card-rating-background-id-@{{$index}} font-weight-bold text-align box-shadow " style="color: white;">
                        $@{{ratingPlan.price}} USD
                     </div>
                     <div ng-show="ratingPlan.is_free" class="card-footer card-rating-background-id-@{{$index}} font-weight-bold text-align box-shadow " style="color: white;">
                         Gratis
                     </div>
                  </div>
                  <div ng-hide="ratingPlan.is_free" class="w-100 text-align trapecio-top card-rating-button-id-@{{$index}} " style="box-shadow: 0 6px 12px 0 rgb(255 255 255), 0 0 0 0 rgba(255, 255, 255, 0); bottom: -25px;">
                     <a ng-href="{{route('/')}}/plan_de_acceso/@{{ratingPlan.id}}/@{{ratingPlan.name_url_value}}" class="col-auto">
                        <span class="fs-0" style="position: absolute;top: -27px;left:-13px;color: white;">Adquirir</span>
                     </a>
                  </div>

                  <div ng-show="ratingPlan.is_free" class="w-100 text-align trapecio-top card-rating-button-id-@{{$index}} " style="box-shadow: 0 6px 12px 0 rgb(255 255 255), 0 0 0 0 rgba(255, 255, 255, 0); bottom: -25px;">
                     <a ng-click="onRatingPlanFree(ratingPlan.id,
                     @if(auth('afiliadoempresa')->user() === null || !auth('afiliadoempresa')->user()->hasAnyRole('tutor'))
                         false
                     @else
                         true
                     @endif
                     )" class="col-auto">
                        <span class="fs-0" style="position: absolute;top: -27px;left:-13px;color: white;">Adquirir</span>
                     </a>
                  </div> 
               </div>
            </div>
           <div class="p-10 text-align border-lg-y col-lg-2 w-100" style="min-height: 13vw; border: 0.4px solid grey; min-width: 100%" ng-hide="ratingPlans.length > 0">
              cargando...
           </div>
         </div>
      </div>
   </div>
</div>

<script src="{{ asset('/../angular/controller/ratingPlanListCtrl.js') }}" defer></script>

@endsection
