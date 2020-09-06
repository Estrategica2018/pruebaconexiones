@extends('roles.student.layout')

@section('content')
    <div class="container ">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                   <div ng-controller="availableSequencesStudentCtrl" ng-init="init(1)">
                        @if (isset($success))
                        <div class="fade-message alert alert-success" role="alert" id="alert1" >
                           @{{ $success }}
                           <button type="button" class="close" aria-label="Close" on-click="alert(document.getElementById('alert1'))">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        @endif
                        @if (isset($errorMessage))
                        <div class="fade-message alert alert-danger" role="alert" id="alert2" >
                           @{{ $errorMessage }}
                           <button type="button" class="close" aria-label="Close" on-click="alert(document.getElementById('alert2'))">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        @endif
                        <div ng-show="errorMessage" class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
                         <span class="col">@{{ errorMessage }}</span>
                         <span class="col-auto"><a ng-click="errorMessage = null"><i class="far fa-times-circle"></a></i></span>
                        </div>

                        <div class="mb-3 card">
                            <div class="card-body">
                               <div class="justify-content-between align-items-center row">
                                    <h5 class="ml-2">Guías de aprendizaje</h5>
                               </div>
                               <a ng-init="guide = true; experience = false"></a>
                                <div ng-show="guide==true" class="d-none-result d-none position-relative card-body pr-1 row" style="min-height: 301px;">
                                   <a class="mt-3 col-lg-2 col-md-3 col-sm-4 col-6" ng-repeat="sequence in sequences"
                                        href="./secuencia/@{{sequence.sequence.id}}/situacion_generadora/@{{sequence.affiliated_account_service_id}}">
                                    <img width="132px" height="auto" src="{{asset('/')}}@{{sequence.sequence.url_image}}" />
                                    <button class="ml-2 mt-2 btn btn-outline-primary fs--2" class="col-6">Explorar</button>
                                   </a>
                                </div>
                                <div ng-show="experience==true" class="d-none-result d-none position-relative card-body pr-1 row" style="min-height: 301px;">
                                   <a class="mt-3 col-lg-2 col-md-3 col-sm-4 col-6" ng-repeat="sequence in sequences"
                                        href="./secuencia/@{{sequence.sequence.id}}/experiencia_cientifica/@{{sequence.affiliated_account_service_id}}">
                                    <img width="132px" height="auto" src="{{asset('/')}}@{{sequence.sequence.url_image}}" />
                                    <button class="ml-2 mt-2 btn btn-outline-primary fs--2" class="col-6">Explorar</button>
                                   </a>
                                </div>
                                <div class="p-3 border-lg-y col-lg-2 w-100" style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-show="sequences === null">
                                  cargando...
                               </div>
                               <div class="d-none-result d-none  p-3 border-lg-y col-lg-2 w-100" style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-show="sequences.length === 0">
                                  No se encontraron secuencias activas...
                               </div>
                               <div class="text-align">
                                 <div class="btn-group" name="btn-guide">
                                    <a ng-click="guide = true ; experience = false">
                                       <button type="button" class="btn btn-primary-guide guidetype active">Guía de aprendizaje</button>
                                    </a>
                                 </div>
                                 <div class="btn-group" name="btn-experience">
                                    <a ng-click="experience = true ; guide = false">
                                       <button type="button" class="btn btn-primary-guide guidetype">Experiencia científica</button>
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
@endsection
@section('js')
    <script src="{{ asset('angular/controller/availableSequencesStudentCtrl.js') }}" defer></script>
@endsection
