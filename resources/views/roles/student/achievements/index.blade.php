@extends('roles.student.achievements.layout')

@section('achievements_layout')
<div class="row p-2 pl-md-4 pr-md-3" ng-controller="achievementsStudentCtrl" ng-init="initSequences(1)" >
    <div class="col-12 mt-sm-2 pr-sm-0 " ng-repeat="sequence in sequences">
            <div class="oval-line"></div>
            <div class="row mt-3"> 
                <div class="col-auto col-md-2 col-xl-1">
                    <img class="imagen-sequence" src="{{asset('/')}}@{{sequence.sequence.url_image}}" width="80px" height= "100px"/>
                </div>
                <div class="col-8 col-md-6 col-xl-4 pr-xl-0 d-block"> 
                    <p class="font-weight-bold mb-1">@{{ 'Guía de aprendizaje ' + ($index + 1) }}</p>
                    <p class="" >@{{sequence.sequence.name}}</p>
                </div>  
                <div class="col-12  col-md-3 col-xl-2 mt-4 mt-lg-0 ml-5 p-xl-0 d-block fs-md--1">
                    <p style="margin-left: -21px;">  Progreso <i class="fa fa-circle mr-2 fs-1" style="color:#6CB249" aria-hidden="true"></i> Concluida </p> 
                    <p style="margin-left: -41px;">  Desempeño <i class="fa fa-circle mr-2 fs-1" style="color:#706B66" aria-hidden="true"></i>Sin iniciar</p> 
                </div>
                <div class="col-12 col-xl-4 mt-4 mt-lg-0 mb-3 d-flex">
                    <div class="col-4 text-align">
                        <a href="/{{auth('afiliadoempresa')->user()->company_name()}}/student/logros_por_secuencia/@{{sequence.affiliated_account_service_id}}/@{{sequence.sequence.id}}">
                            <div class="col-12 border-left-mini">
                                <img src="{{asset('images/icons/reporteSecuencias.png')}}" class="imagen-reports-type-mini ml-lg-2"  width="45px" height= "auto"/>
                            </div>
                            <div class="font-weight-bold m-auto w-50 pt-3 fs--1 fs-xl--2 ml-xl-2" style="min-width: 106px;" >Reporte por guía de aprendizaje</div>
                        </a>
                    </div>
                    <div class="col-4 text-align">
                        <a href="/{{auth('afiliadoempresa')->user()->company_name()}}/student/logros_por_momento/@{{sequence.affiliated_account_service_id}}/@{{sequence.sequence.id}}">
                            <div class="col-12 border-left-mini">
                                <img src="{{asset('images/icons/reporteMomentos.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                            </div>
                            <div class="font-weight-bold m-auto w-25 pt-3 fs--1 fs-xl--2" style="min-width: 106px;" >Reporte por momento</div>
                        </a>
                    </div>
                    <div class="col-4 text-align">
                        <a href="/{{auth('afiliadoempresa')->user()->company_name()}}/student/logros_por_pregunta/@{{sequence.affiliated_account_service_id}}/@{{sequence.sequence.id}}">
                            <div class="col-12 border-left-mini">
                                <img src="{{asset('images/icons/reportePreguntas.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                            </div>
                            <div class="font-weight-bold m-auto w-25 pt-3 fs--1 fs-xl--2" style="min-width: 106px;">Reporte por preguntas</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 sequences-line" ng-show="sequences.length === 0">
       <div class="oval-line mb-4"></div>
       <h6>Aún no cuentas con guías de aprendizaje activas.</h6>
    </div>
</div>
@endsection
@section('js')
    <script src="{{asset('/../angular/controller/achievementsStudentCtrl.js')}}"></script>
@endsection
