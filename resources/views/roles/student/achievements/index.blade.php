@extends('roles.student.achievements.layout')

@section('achievements_layout')
<div class="row p-2 pl-md-4 pr-md-3" ng-controller="achievementsStudentCtrl" ng-init="initSequences(1)" >
    @foreach($accountServices as $index=>$accountService)
    <div class="col-12 mt-sm-2 pr-sm-0 ">
        <div class="oval-line"></div>
        <div class="row mt-3"> 
            <div class="col-auto col-md-2 col-xl-1">
                <img class="imagen-sequence" src="{{asset($accountService->sequence->url_image)}}" width="80px" height= "100px"/>
            </div>
            <div class="col-8 col-md-5 col-xl-4 pr-xl-0 d-block"> 
                <p class="font-weight-bold mb-1">{{ 'Guía de aprendizaje ' . ($index + 1) }}</p>
                <p class="">{{$accountService->sequence->name}}</p>
            </div>
            <div class="col-12 col-lg-3 col-md-2 col-xl-2 mt-4 mt-lg-1 mt-xl-0 ml-5 p-xl-0 d-block fs-md--1" style="min-width: 161px;">
                <label class="" style="margin-left: -21px;"><strong> Progreso</strong></label> 
                @if(isset($accountService->sequence['progress']))
                    @if($accountService->sequence['progress']==0)
                        <i class="fa fa-circle mr-2 fs-1" style="color:#706B66" aria-hidden="true"></i><label class="">Sin iniciar</label>
                    @endif
                    @if($accountService->sequence['progress']>0 && $accountService->sequence['progress']<100)
                        <i class="fa fa-circle mr-2 fs-1" style="color:#F9E538" aria-hidden="true"></i> <label class="">En proceso</label>
                    @endif
                    @if($accountService->sequence['progress']==100)
                    <i class="fa fa-circle mr-2 fs-1" style="color:#6CB249" aria-hidden="true"></i> <label class="">Concluida</label>
                    @endif
                @else
                    <i class="fa fa-circle mr-2 fs-1" style="color:#706B66" aria-hidden="true"></i><label class="">Sin iniciar</label>
                @endif                 
                 
                @if(isset($accountService->sequence['performance'] ))
                    <label  style="margin-left: -35px;"> <strong> Desempeño</strong></label>
                    @if($accountService->sequence['performance']>=0) 
                        @if($accountService->sequence['performance']>=90)
                        <i class="fa fa-circle mr-2 fs-1" style="color:#6CB249" aria-hidden="true"></i> (S) 
                        @endif
                        @if($accountService->sequence['performance']>=70 && $accountService->sequence['performance']<=89)
                        <i class="fa fa-circle  mr-2 fs-1" style="color:#6CB249" aria-hidden="true"></i> (A) 
                        @endif
                        @if($accountService->sequence['performance']>=60 && $accountService->sequence['performance']<=69)
                        <i class="fa fa-circle mr-2 fs-1" style="color:#F9E538" aria-hidden="true"></i> (B)  
                        @endif
                        @if($accountService->sequence['performance']>=40 && $accountService->sequence['performance']<=59)
                        <i class="fa fa-circle mr-2 fs-1" style="color:#AC312A" aria-hidden="true"></i> (B)  
                        @endif
                        @if($accountService->sequence['performance']<40)
                        <i class="fa fa-circle mr-2 fs-1" style="color:#AC312A" aria-hidden="true"></i> (B)  
                        @endif
                        {{$accountService->sequence['performance']}} %
                    @else  
                        <i class="fa fa-circle mr-2 fs-1" style="color:#706B66" aria-hidden="true"></i><label class="">Sin iniciar</label>
                    @endif
                @endif 
            </div>
            <div class="col-12 col-xl-4 mt-4 mt-lg-0 mb-3 d-flex">
                <div class="col-0 text-align">
                    <div class="mb-2">
                        <a href="{{
                            route('student.achievements.sequence',
                            ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                            'affiliated_account_service_id'=>$accountService->affiliated_account_service_id,
                            'sequence_id'=>$accountService->sequence->id
                            ])}}"> 
                            <img src="{{asset('images/icons/reporteSecuencias.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                        </a>
                    </div>
                    <a href="{{
                        route('student.achievements.sequence',
                        ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                        'affiliated_account_service_id'=>$accountService->affiliated_account_service_id,
                        'sequence_id'=>$accountService->sequence->id
                        ])}}"> 
                        <label class="cursor-pointer font-weight-bold fs--1" style="width: 102px;">Reporte por guía de aprendizaje</label>
                    </a>
                </div>
                <div class="col-0 text-align">
                    <div class="mb-2">
                        <a href="{{
                            route('student.achievements.moment',
                            ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                            'affiliated_account_service_id'=>$accountService->affiliated_account_service_id,
                            'sequence_id'=>$accountService->sequence->id
                            ])}}"> 
                            <img src="{{asset('images/icons/reporteMomentos.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                        </a>
                    </div>
                    <a href="{{
                        route('student.achievements.moment',
                        ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                        'affiliated_account_service_id'=>$accountService->affiliated_account_service_id,
                        'sequence_id'=>$accountService->sequence->id
                        ])}}"> 
                        <label class="cursor-pointer font-weight-bold fs--1" style="width: 102px;">Reporte por momento</label>
                    </a>
                </div>
                <div class="col-0 text-align">
                    <div class="mb-2">
                        <a href="{{
                            route('student.achievements.question',
                            ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                            'affiliated_account_service_id'=>$accountService->affiliated_account_service_id,
                            'sequence_id'=>$accountService->sequence->id
                            ])}}"> 
                            <img src="{{asset('images/icons/reportePreguntas.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                        </a>
                    </div>
                    <a href="{{
                        route('student.achievements.question',
                        ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                        'affiliated_account_service_id'=>$accountService->affiliated_account_service_id,
                        'sequence_id'=>$accountService->sequence->id
                        ])}}"> 
                        <label class="cursor-pointer font-weight-bold fs--1" style="width: 102px;">Reporte por preguntas</label>
                    </a>
                </div>
            </div>
        </div>
    </div> 
    @endforeach
    @if(count($accountServices) == 0)
    <div class="col-12 sequences-line">
       <div class="oval-line mb-4"></div>
       <h6>Aún no cuentas con guías de aprendizaje activas.</h6>
    </div>
    @endif
</div>
@endsection
@section('js')
    <script src="{{asset('/../angular/controller/achievementsStudentCtrl.js')}}"></script>
@endsection
