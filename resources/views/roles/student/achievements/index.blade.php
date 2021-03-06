@extends('roles.student.achievements.layout')

@section('achievements_layout')
<div class="row p-2 pl-md-4 pr-md-3" ng-controller="achievementsStudentCtrl" ng-init="initSequences(1)" >
    @foreach($accountServices as $index=>$accountService)
    <div class="col-12 mt-sm-2 pr-sm-0 " style="@if($accountService->is_active != 1) opacity:65%; @endif">
        <div class="oval-line"></div>
        <div class="row mt-3"> 
            <div class="col-2 col-md-auto">
                <img class="imagen-sequence" src="{{asset($accountService->sequence->url_image)}}" width="80px" height= "100px"/>
            </div>
            <div class="col-6 col-md-4 col-xl-3 pr-xl-0 d-block"> 
                <p class="font-weight-bold mb-1">{{ 'Guía de aprendizaje ' . ($index + 1) }}</p>
                <p class="">{{$accountService->sequence->name}}</p>
                @if($accountService->is_active != 1) 
                    <p class="">Fecha Expiración: {{$accountService->end_date}}</p>
                @endif
            </div>
            @if($accountService->rating_plan->is_free) 
                <div class="position-absolute label_free" style="top: 39px;">
                       Prueba gratuita
                </div>
            @endif
            <div class="col-3 col-lg-3 col-md-2 col-xl-2 mt-1 mt-md-1 mt-xl-0 ml-7 p-xl-0 d-block fs-md--1" style="min-width: 161px;">
                <h6 class="" style="margin-left: -62px;"><strong> Progreso</strong> 
                @if(isset($accountService->sequence['progress'])) 
                    @if($accountService->sequence['progress']==0)
                        <i class="fa fa-circle mr-2 fs-1" style="color:#808080" aria-hidden="true"></i><label class="">Sin iniciar</label>
                    @endif
                    @if($accountService->sequence['progress']>0 && $accountService->sequence['progress']<100)
                        <i class="fa fa-circle mr-2 fs-1" style="color:#F9E538" aria-hidden="true"></i> <label class="">En proceso</label>
                    @endif
                    @if($accountService->sequence['progress']==100)
                    <i class="fa fa-circle mr-2 fs-1" style="color:#6CB249" aria-hidden="true"></i> <label class="">Concluida</label>
                    @endif
                @else
                    <i class="fa fa-circle mr-2 fs-1" style="color:#808080" aria-hidden="true"></i><label class="">Sin iniciar</label>
                @endif            
				</h6>
                
    
                @if(isset($accountService->sequence['performance'] ) && $accountService->rating_plan_type != 3)
				
                    <h6  style="margin-left: -82px;"> <strong class="ml-lg-0 ml-md-1 ml-3"> Desempeño</strong>
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
                        <i class="fa fa-circle mr-2 fs-1" style="color:#808080" aria-hidden="true"></i><label class="">Sin iniciar</label>
                    @endif
					
					</h6>
                @endif 
            </div>
            <div class="mt-md-fix mt-lg-fix col-12 col-xl-4 mt-4 mt-lg-0 mb-3 d-flex">
                <div class="col-0 text-align">
                    <div class="mb-2">
                        <a href="{{
                            route('student.achievements.sequence',
                            ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                            'affiliated_account_service_id'=>$accountService->id,
                            'sequence_id'=>$accountService->sequence->id
                            ])}}"> 
                            <img src="{{asset('images/icons/reporteSecuencias.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                        </a>
                    </div>
                    <a href="{{
                        route('student.achievements.sequence',
                        ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                        'affiliated_account_service_id'=>$accountService->id,
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
                            'affiliated_account_service_id'=>$accountService->id,
                            'sequence_id'=>$accountService->sequence->id
                            ])}}"> 
                            <img src="{{asset('images/icons/reporteMomentos.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                        </a>
                    </div>
                    <a href="{{
                        route('student.achievements.moment',
                        ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                        'affiliated_account_service_id'=>$accountService->id,
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
                            'affiliated_account_service_id'=>$accountService->id,
                            'sequence_id'=>$accountService->sequence->id
                            ])}}"> 
                            <img src="{{asset('images/icons/reportePreguntas.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                        </a>
                    </div>
                    <a href="{{
                        route('student.achievements.question',
                        ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                        'affiliated_account_service_id'=>$accountService->id,
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
