@extends('roles.tutor.layout')

@section('content-tutor-index')
   <div class="d-none-result d-none" ng-controller="tutorProductsCtrl" ng-init="init()" >
        <div class="row no-gutters" ng-show="products && products.length > 0">
          <h6 ng-show="products.length === 1" class="mt-3 mb-4"> Actualmente cuentas con el siguiente producto con nosotros.</h6>
          <h6 ng-show="products.length > 1" class="mt-3 mb-4"> Actualmente cuentas con diferentes productos con nosotros.</h6>
          <div class="row">
              <div ng-repeat="product in products" class="col-lg-6 col-12 mb-3">
                <div ng-show="product.type_product_id === 1" class="d-flex">
                    <img width="auto" height="100px" src="{{asset('/')}}@{{product.sequence.url_image}}" />
                    <div class="row">
                       <h6 class="col-12 ml-3">Secuencia @{{product.sequence.name}}</h6>
                       <p class="fs--3 ml-3 col-12  pr-5">Esta guía de aprendizaje consta de : Situación generadora, guía de saberes, ruta de viaje y los 8 momentos que contienen : Pregunta central, ciencia en contexto, experiencia cientíﬁcas y + conexiones</p>
                    </div>
                </div>
                <div ng-show="product.type_product_id === 2" class="d-flex">
                    <img width="auto" height="100px" src="{{asset('/')}}@{{product.sequence.url_image}}" />
                    <div class="row">
                       <h6 class="col-12 ml-3">Experiencia @{{product.sequence.name}}</h6>
                       <p class="fs--3 ml-3 col-12  pr-5">Consta de diversas experiencias científicas</p>
                    </div>
                </div>
                <div ng-show="product.type_product_id === 3" class="d-flex">
                    <img width="auto" height="100px" src="{{asset('/')}}@{{product.sequence.url_image}}" />
                    <div class="row">
                       <h6 class="col-12 ml-3">Momentos de @{{product.sequence.name}}</h6>
                       <p class="fs--3 ml-3 col-12 pr-5">Consta de diveros momentos</p>
                    </div>
                </div>
                <div ng-show="product.affiliated_account_services.rating_plan.is_free" class="position-absolute label_free">
                       Prueba gratuita
                </div>
              </div>
           </div>
        </div>
        <div class="fs--1" ng-show="products && products.length === 0">
          <h6>Aún no cuentas con productos con nosotros</h6>
        </div>
        <div class="p-3 border-lg-y col-lg-2 w-100"
               style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-hide="products">
               cargando...
        </div>
        <div class="p-3 border-lg-y col-lg-2 w-100"
               style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-hide="ratingPlans">
               cargando...
        </div>
        
        <div class="no-gutters" ng-show="ratingPlans && ratingPlans.length > 0">
          <h6 class="mt-3 mb-4"> Recuerda nuestros planes y beneﬁcios para ampliar las posibilidades de aprendizaje.</h6>
          <div class="row">
              <div ng-hide="ratingPlan.is_free"class="mb-6 col-xl-3 col-6 col-md-4" 
                  ng-repeat="ratingPlan in ratingPlans">
                    <div class="card-header card-rating-background-id-@{{$index}} mt-3 fs--3 flex-100 box-shadow ">
                        <h6 class="font-weight-bold text-center fs--3 card-rating-plan-id-@{{$index}}"> 
                            <span class="ml-2 " style="font-size:15px;color: white;"> @{{ratingPlan.name}} </span>
                        </h6>
                    </div> 
                    <div class="card-body bg-light pr-2 pl-2 pb-0 w-100 box-shadow " style="min-height: 182px;">
                        <ul class="p-0 ml-2" ng-repeat="item in ratingPlan.description_items">
                            <li class="fs-1 small pl-1 pr-2 mt-3 ml-3 card-rating-plan-id-@{{$parent.$index}}" style="line-height: 17px;"> 
                            <span class="color-gray-dark font-family font-14px ">
                            @{{item}}
                            </span></li>
                        </ul>
                    </div> 
                    <div ng-hide="ratingPlan.is_free" class="card-footer card-rating-background-id-@{{$index}} font-weight-bold text-align box-shadow " style="color: white;">
                            $@{{ratingPlan.price}} USD
                        </div>
                        <div ng-show="ratingPlan.is_free" class="card-footer card-rating-background-id-@{{$index}} font-weight-bold text-align box-shadow " style="color: white;">
                            Gratis
                        </div>
                        <div ng-hide="ratingPlan.is_free" class="w-75 trapecio-top position-absolute card-rating-button-id-@{{$index}} " style="right: 12%;box-shadow: 0 6px 12px 0 rgb(255 255 255), 0 0 0 0 rgba(255, 255, 255, 0);">
                              <a  ng-href="{{route('/')}}/plan_de_acceso/@{{ratingPlan.id}}/@{{ratingPlan.name_url_value}}" class="col-auto" >
                                    <span class="fs--3  mt-2" style="position: absolute;top: -31px;color: white;">Adquirir</span>
                              </a>
                        </div>
                        <div ng-show="ratingPlan.is_free" class="w-75 trapecio-top position-absolute card-rating-button-id-@{{$index}} " style= "right: 12%;box-shadow: 0 6px 12px 0 rgb(255 255 255), 0 0 0 0 rgba(255, 255, 255, 0);">
                              <a ng-click="onRatingPlanFree(ratingPlan.id)"class="col-auto" >
                                    <span class="fs--3  mt-2" style="position: absolute;top: -31px;color: white; ">Adquirir</span>
                              </a>
                        </div> 
               </div>
           </div>
        </div>
   </div>
@endsection
@section('js')
    <script src="{{asset('/../angular/controller/tutorProductsCtrl.js')}}"></script>
@endsection