@extends('roles.student.layout')

@section('content')
    <div class="container ">
        <div class="content">
            <div ng-controller="availableExperiencesStudentCtrl" ng-init="init({{auth('afiliadoempresa')->user()->company_id()}},
                {{$sequence_id}}, {{$account_service_id}})">
                <div class="row conx-page">
                    <div id="loading" class="z-index-10 position-absolute card card-body p-10 w-100 text-align" ng-hide="sequence"
                      style="min-height: 100%;" >
                      cargando...
                    </div>
                    <div class="col-12 col-lg-8 row" ng-show="sequence">
                        <div class="h-100 h-lg-20 w-100">
                            <iframe class="col-12" id="vimeo-player" frameborder="0" width="100%" height="100%" refreshable="sequence.url_vimeo"
                            
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                            </iframe>
                            <div class="card-body d-flex col-12 row no-gutters">
                                <div class="col-auto">
                                    <img src="/@{{sequence.url_image}}" style="width:auto; height:auto;">
                                </div>
                                <div class="col-8">
                                    <label class="ml-4 card-title">@{{momentPart.title}}</label>
                                    <p>
                                    <label class="ml-4 card-title fs-0">Momento @{{moment.order + '. ' + moment.moment_name}}</label>
                                    </p>
                                    <h6 class="ml-4 card-title">@{{sequence.name}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" card col-12 col-lg-4 row card-body shadow-none">
                        <div class="card-body bg-light mb-1" ng-repeat = "moment in moments">
                            <div class="row " style="min-height: 100px;">
                                <div class="col-2-3 col-lg-5">
                                    <img src="/@{{moment.url_image_experience || 'images/icons/NoImageAvailable.jpeg'}}" width="100%" height="auto" style="min-width: 90px;">
                                </div>
                                <div class="col-7">
                                    <h6>@{{moment.order}}. @{{moment.moment_name}}</h6>
                                    <p class="card-text">
                                        <small ng-repeat="part in moment.parts"> <a href="#" ng-click="changeVideo(part, moment)"> Parte @{{part.part_id }} <span ng-show="$index < moment.parts.length -1 ">|</span> </a> </small>
                                    </p>
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
    <script src="{{ asset('angular/controller/availableExperiencesStudentCtrl.js') }}" defer></script>
    <script src="{{ asset('js/vimeo-player.min.js') }}" defer></script>
@endsection
