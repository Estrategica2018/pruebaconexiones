@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div class="w-100" ng-controller="ratingPlanDetailCtrl" ng-init="init(1,'{{$rating_plan_id}}','{{$sequence_id}}')">
    <div ng-show="errorMessageFilter" id="errorMessageFilter"
      class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
      <span class="col">@{{ errorMessageFilter }}</span>
      <span class="col-auto"><a ng-click="errorMessageFilter = null"><i class="far fa-times-circle"></i></a></span>
    </div>
    <div id="loading" class="text-align border-lg-y col-lg-2 w-100 card card-body" ng-hide="sequences"
       style="min-height: 30vw; border: 0.4px solid grey; min-width: 100%; padding: 30%;">
       cargando...  
    </div>
    <div class="mb-3 card col-12" ng-show="sequences">
      <div class="card-body w-100">
        <div class="row d-none-result d-none">
             <div class="mb-3">
                <h4 class=" boder-header p-1 pl-3">
                   @{{ratingPlan.name}} 
                </h4>
             </div>
                          
             <ul class="text-justify pr-4 pl-3 mb-0">
                <p> @{{ratingPlan.description}}</p>
                <p>A continuación, te mostramos las <strong>guías de aprendizaje</strong> disponibles. Para conocer de qué se tratan y cuáles son sus contenidos pueden hacer clic en <strong>ver detalle</strong>, allí encontrarán un video introductorio y una <strong>malla curricular</strong> en la que se describen los <strong>propósitos de cada momento</strong>, la <strong>pregunta central</strong>, el eje temático de la <strong>explicación de ciencia en contexto</strong>, las <strong>experiencias científicas</strong> propuestas y los <strong>materiales</strong> que se requieren para esta.</p>
                <p>Si tienes alguna pregunta puedes escribirnos a través del formulario de  <a target="_blank" href="{{route('contactus')}}"><strong class="text-underline">contáctenos</strong></a>. </p>
             </ul>
             
             <div class="col-12 text-right r-0 w-md-50" id="div-continue" style="background-color: white; z-index: 10; ">
                <span class="mt-1">@{{messageToast}}</span>
                <span class="mt-1 d-block font-weight-bold" ng-show="messageToastPrice">@{{messageToastPrice}}</span>
                <button ng-click="onContinueElements()" ng-class="{'disabled':!selectComplete}" class="d-none-result d-none ml-3 mt-3 btn btn-outline-primary fs-0 confirm_rating" href="#" class="col-6">
                   <i class="fas fa-arrow-right"></i> Continuar compra
                </button>
             </div>
             
             <div class="col-12 ml-2 mt-1 row p-0 h-100 ml-0 mr-0" ng-show="sequences" style="min-height: 500px;">
                <!-- Toast -->
                <div class="z-index-10 bg-success position-absolute color-white p-3" id="toast-name-1">
                  <div hg-hide="messageToastPrice" class="price">
                    @{{messageToastPrice}}
                  </div> 
                  @{{messageToast}}
                 </div>
              
               <div class="p-0 col-md-6 col-sm-12" ng-show="sequenceForAdd" style="border: 10px solid white;">
                   <div class="d-none-result d-none row w-100">
                      <div class="ml-2 pr-2 border-white-extent card card-body bg-dark row d-flex card-boody-sequence" >
                        <div class="view" id="sequence-description-@{{sequenceForAdd.id}}">
                          <div class="media">
                             <div class="row col-auto">
                                  <img ng-src="{{asset('/')}}@{{sequenceForAdd.url_image}}" width="142px" height="auto" style="width:142px"/>
                             </div>
                            <div class="position-absolute" style="top:10px; transform : scale(2);">
                              <input type="checkbox" ng-show="ratingPlan.type_rating_plan_id === 1" class="sequence_ForAdd" ng-model="sequenceForAdd.isSelected" name="check_sequence_ForAdd_"@{{sequenceForAdd.id}} ng-change="onCheckChange(sequenceForAdd,null,sequenceForAdd)"/>
                             </div>
                            <div class="pl-2 ml-3 ml-md-2">
                               <h5 class="pl-2 fs-0 boder-header text-align-left">@{{sequenceForAdd.name}}</h5>
                               <div class="mt-3 pr-2 pl-2 fs--1" style="min-height: 110px;">@{{sequenceForAdd.description}}</div>
                               <div class="col-12">
                                <a ng-click="showMash(sequenceForAdd)" class="ml-3 mt-3 btn btn-outline-primary fs--2" href="#" class="col-6">
                                     <i class="fas fa-search"></i> Ver detalle
                                 </a>
                                 <a ng-click="showVideo(sequenceForAdd)" class="ml-3 mt-3 btn btn-outline-primary fs--2" href="#" class="col-6">
                                     <i class="fas fa-search"></i> Ver video
                                 </a>
                                 
                                <div class="fade show bg-light mt-2 row p-3" ng-show="ratingPlan.type_rating_plan_id === 2 || ratingPlan.type_rating_plan_id === 3" id="moment_div_responsive_ForAdd" style="margin-left: -19vh;     margin-right: 15px;"> 
                                     <div class="text-left mt-2" ng-repeat="moment in sequenceForAdd.moments" ng-show="ratingPlan.type_rating_plan_id === 2">
                                         <input class="transform-scale-2 ml-3 mt-1 mr-2" type="checkbox" ng-model="moment.isSelected" name="check_moment_ForAdd@{{moment.id}}" ng-change="onCheckChange(sequenceForAdd,moment,sequenceForAdd)"/>
                                         <span class="fs--1">@{{moment.name}}</span>
                                     </div>
                                     <div class="text-left mt-2" ng-repeat="moment in sequenceForAdd.moments"  ng-show="ratingPlan.type_rating_plan_id === 3">
                                         <input class="transform-scale-2 ml-3 mt-1 mr-2" type="checkbox" ng-model="moment.isSelected" name="check_experience_ForAdd@{{moment.id}}" ng-change="onCheckChange(sequenceForAdd,moment,sequenceForAdd)"/>
                                         <span class="fs--1">@{{moment.name}}</span>
                                     </div>
                                 </div>
                         
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                   </div>
               </div>
               <div class="p-0 col-md-6 col-sm-12" style="border: 10px solid white;" ng-repeat="sequence in sequences">
                   <div class="row w-100 p-0">
                      <div class="ml-2 pr-2 border-white-extent card card-body bg-dark row d-flex card-boody-sequence" style="min-height: 297px;">
                        <div class="view" id="sequence-description-@{{sequence.id}}">
                          <div class="media">
                            <div class="row col-auto">
                                 <img ng-src="{{asset('/')}}@{{sequence.url_image}}" width="142px" height="auto" style="width:142px"/>
                             </div>
                             <div class="position-absolute" style="top:10px; transform : scale(2);">
                               <input type="checkbox" ng-show="ratingPlan.type_rating_plan_id === 1" ng-model="sequence.isSelected" name="check_sequence_"@{{sequences.id}} ng-change="onCheckChange(sequence)"/>
                              </div>
                                 <div class="pl-2 ml-3 ml-md-2">
                                 <h5 class="pl-2 fs-0 boder-header text-align-left">@{{sequence.name}}</h5>
                                 <div class="mt-3 pr-2 pl-2 fs--1" style="min-height: 110px;">@{{sequence.description}}</div>
                                 <div class="col-12 p-0">
                                     <a ng-click="showMash(sequence)" class="ml-3 mt-3 btn btn-outline-primary fs--1" href="#" class="col-6">
                                         <i class="fas fa-search"></i> Ver detalle
                                     </a>
                                     <a ng-click="showVideo(sequence)" class="ml-3 mt-3 btn btn-outline-primary fs--1" href="#" class="col-6">
                                         <i class="fas fa-search"></i> Ver video
                                     </a>
                                     
                                    <div class="fade show bg-light mt-2 row p-3" ng-show="ratingPlan.type_rating_plan_id === 2 || ratingPlan.type_rating_plan_id === 3" id="moment_div_responsive_@{{sequence.id}}" style="margin-left: -19vh;     margin-right: 15px;"> 
                                         <div class="text-left mt-2" ng-repeat="moment in sequence.moments" ng-show="ratingPlan.type_rating_plan_id === 2">
                                             <input class="transform-scale-2 ml-3 mt-1 mr-2" type="checkbox" ng-model="moment.isSelected" name="check_moment_ForAdd@{{moment.id}}" ng-change="onCheckChange(sequence,moment,sequence)"/>
                                             <span class="fs--1">@{{moment.name}}</span>
                                         </div>
                                         <div class="text-left mt-2" ng-repeat="moment in sequence.moments"  ng-show="ratingPlan.type_rating_plan_id === 3">
                                             <input class="transform-scale-2 ml-3 mt-1 mr-2" type="checkbox" ng-model="moment.isSelected" name="check_experience_ForAdd@{{moment.id}}" ng-change="onCheckChange(sequence,moment,sequence)"/>
                                             <span class="fs--1">@{{moment.name}}</span>
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
             
             <div class="col-12 text-right">
                <span class="mt-1 font-weight-bold" ng-show="messageToastPrice">@{{messageToastPrice}}</span>
                <button ng-click="onContinueElements()" ng-class="{'disabled':!selectComplete}" class="d-none-result d-none ml-3 mt-3 btn btn-outline-primary fs-0 confirm_rating" href="#" class="col-6"><i class="fas fa-arrow-right"></i> 
                Continuar compra
                </button>
             </div>
        </div>


      </div>
      @include('ratingPlan/elements_kits')
   </div>
   
</div>

<script src="{{ asset('/../angular/controller/ratingPlanDetailCtrl.js') }}" defer></script>
<script src="{{ asset('/../js/math.round_10.min.js') }}" defer></script>

@endsection