@extends('roles.student.achievements.layout')

@section('achievements_layout')
<div class="row p-2 pl-md-4 pr-md-3" ng-controller="achievementsStudentCtrl" ng-init="initSequences(1)" >
    <div class="col-12 mt-sm-2 pr-sm-0 " ng-repeat="sequence in sequences">
        <div class="oval-line"></div>
        <div class="d-flex pt-2 p-md-3">
            <img class="imagen-sequence" 
            src="{{asset('/')}}@{{sequence.sequence.url_image}}" width="80px" height= "100px"/>
            <div class="d-block col-5 mr-2 ml-2 fs--1">
                <p class="font-weight-bold mb-1">@{{ 'Guía de aprendizaje ' + ($index + 1) }}</p>
                <p class="" >@{{sequence.sequence.name}}</p>
            </div>
            <div class="d-block col-2-2 text-align">
			    <a href="/{{auth('afiliadoempresa')->user()->company_name()}}/student/logros_por_secuencia/@{{sequence.affiliated_account_service_id}}/@{{sequence.sequence.id}}">
                    <div class="col-12 border-left-mini">
                        <img src="{{asset('images/icons/reporteSecuencias.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                    </div>
                    <div class="font-weight-bold p-3 fs--2">Reporte por guía de aprendizaje</div>
                </a>
            </div>
            <div class="d-block col-2-2 text-align">
                <a href="/{{auth('afiliadoempresa')->user()->company_name()}}/student/logros_por_momento/@{{sequence.affiliated_account_service_id}}/@{{sequence.sequence.id}}">
                    <div class="col-12 border-left-mini">
                        <img src="{{asset('images/icons/reporteMomentos.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                    </div>
                    <div class="font-weight-bold p-3 fs--2">Reporte por momento</div>
                </a>
            </div>
            <div class="d-block col-2-2 text-align">
                <a href="/{{auth('afiliadoempresa')->user()->company_name()}}/student/logros_por_pregunta/@{{sequence.affiliated_account_service_id}}/@{{sequence.sequence.id}}">
                    <div class="col-12 border-left-mini">
                        <img src="{{asset('images/icons/reportePreguntas.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                    </div>
                    <div class="font-weight-bold p-3  fs--2">Reporte por preguntas</div>
                </a>
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
