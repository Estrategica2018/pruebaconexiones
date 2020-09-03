@extends('roles.student.achievements.layout')

@section('achievements_layout')
<div class="row p-2 pl-md-4 pr-md-3" ng-controller="achievementsStudentCtrl" ng-init="initSequences(1)" >
    @if(isset($sequence))
        <div class="col-12 mt-sm-2 pr-sm-0 " >
            <div class="oval-line"></div>
            <button class="btn btn-sm fs-1">
                <a href="{{route('student.achievements',auth('afiliadoempresa')->user()->company_name())}}"><i class="fas fa-arrow-left"></i>
                </a>
            </button>
            <div class="row">
                <div class="col-auto">
                    <img class="imagen-sequence" src="{{asset($sequence['url_image'])}}" width="80px" height= "100px"/>
                </div>
                <div class="col-5 col-xl-4 mr-2 ml-2 fs--1">
                    <p class="font-weight-bold mb-1">Guía de aprendizaje</p>
                    <p class="fs-0" >{{$sequence['name']}}</p>
                </div>
                <div class="col-1 mt-3 mt-md-0 ml-5 ml-md-0" style="min-width: 186px;">
                     <label class="mt-md-fix" style="margin-left: -21px;"><strong> Progreso</strong></label> 
                    @if(isset($sequence['progress']))
                        @if($sequence['progress']==0)
                            <i class="fa fa-circle mr-2 fs-1" style="color:#706B66" aria-hidden="true"></i><label class="">Sin iniciar</label>
                        @endif
                        @if($sequence['progress']>0 && $sequence['progress']<100)
                            <i class="fa fa-circle mr-2 fs-1" style="color:#F9E538" aria-hidden="true"></i> <label class="">En proceso</label>
                        @endif
                        @if($sequence['progress']==100)
                        <i class="fa fa-circle mr-2 fs-1" style="color:#6CB249" aria-hidden="true"></i> <label class="">Concluida</label>
                        @endif
                    @else
                        <i class="fa fa-circle mr-2 fs-1" style="color:#706B66" aria-hidden="true"></i><label class="">Sin iniciar</label>
                    @endif  
                    @if($sequence['progress']>0) 
                        <label class="" style="margin-left: -35px;"><strong> Desempeño</strong></label> 
                        @if(isset($sequence['performance'] ))
                            @if($sequence['performance']>=90)
                            <i class="fa fa-circle mr-2 fs-1" style="color:#6CB249" aria-hidden="true"></i> (S)
                            @endif
                            @if($sequence['performance']>=70 && $sequence['performance']<=89)
                            <i class="fa fa-circle  mr-2 fs-1" style="color:#6CB249" aria-hidden="true"></i> (A) 
                            @endif
                            @if($sequence['performance']>=60 && $sequence['performance']<=69)
                            <i class="fa fa-circle mr-2 fs-1" style="color:#F9E538" aria-hidden="true"></i> (B) 
                            @endif
                            @if($sequence['performance']>=40 && $sequence['performance']<=59)
                            <i class="fa fa-circle mr-2 fs-1" style="color:#AC312A" aria-hidden="true"></i> (B)
                            @endif
                            @if($sequence['performance']> 0  && $sequence['performance']<40)
                            <i class="fa fa-circle mr-2 fs-1" style="color:#AC312A" aria-hidden="true"></i> (B)
                            @endif
                            @if($sequence['performance']==0)
                            <i class="fa fa-circle mr-2 fs-1" style="color:#706B66" aria-hidden="true"></i><label class="">Sin iniciar</label>
                            @endif

                            <span class="fs-0">{{$sequence['performance']}} %</span>
                            
                        @endif
                    @endif
                </div>
                <div class="col-12 col-xl-4 row mt-4 mt-xl-0 text-align " style="min-width: 408px;">
                    <div class="col-4 border-left-mini"> 
                        <div class="mb-2">
                            <a href="{{
                            route('student.achievements.sequence',
                            ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                                'affiliated_account_service_id'=>$affiliated_account_service_id,
                                'sequence_id'=>$sequence['id']
                            ])}}">
                                <img src="{{asset('images/icons/reporteSecuencias.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                            </a>
                        </div>
                        <a href="{{
                        route('student.achievements.sequence',
                        ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                            'affiliated_account_service_id'=>$affiliated_account_service_id,
                            'sequence_id'=>$sequence['id']
                        ])}}">
                            <label class="cursor-pointer font-weight-bold fs--1" style="width: 102px;">Reporte por guía de aprendizaje</label>
                        </a>
                    </div>

                    <div class="col-4 border-left-mini"> 
                        <div class="mb-2">
                            <a href="{{
                            route('student.achievements.moment',
                            ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                                'affiliated_account_service_id'=>$affiliated_account_service_id,
                                'sequence_id'=>$sequence['id']
                            ])}}">
                                <img src="{{asset('images/icons/reporteMomentos.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                            </a>
                        </div>
                        <a href="{{
                        route('student.achievements.moment',
                        ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                            'affiliated_account_service_id'=>$affiliated_account_service_id,
                            'sequence_id'=>$sequence['id']
                        ])}}">
                            <label class="cursor-pointer font-weight-bold fs--1" style="width: 102px;">Reporte por momento</label>
                        </a>
                    </div> 
                    <div class="col-4 border-left-mini"> 
                        <div class="mb-2">
                            <a href="{{
                                route('student.achievements.question',
                                ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                                'affiliated_account_service_id'=>$affiliated_account_service_id,
                                'sequence_id'=>$sequence['id']
                                ])}}"> 
                                <img src="{{asset('images/icons/reportePreguntas.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                            </a>
                        </div>
                        <a href="{{
                            route('student.achievements.question',
                            ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                            'affiliated_account_service_id'=>$affiliated_account_service_id,
                            'sequence_id'=>$sequence['id']
                            ])}}"> 
                            <label class="cursor-pointer font-weight-bold fs--1" style="width: 102px;">Reporte por preguntas</label>
                        </a>
                    </div> 
                </div> 
            </div> 
            <h5 class="mt-3  mb-3">Reporte por preguntas</h5>
			
			<div class="p-3 border-lg-y col-lg-2 w-100"
               style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-hide="loadFinish">
               cargando...
            </div>
        
            @foreach ($moments as $key => $moment)
               <div class="row p-3 rounded @if($key%2==0) bg-soft-dark @else bg-soft-dark-light @endif d-none-result d-none">

                    <div class="col-12 d-flex fs-0 font-weight-bold">
                        <span class="col-4"> Momento {{$moment['order']}} <small>{{$moment['name']}}</small></span>
                        <div class="col-3 col-md-4">
                            @if($moment['progress']==0)
                            <i class="fa fa-circle mr-2 fs-2" style="color:#706B66" aria-hidden="true"></i><span class="fs--1">Sin iniciar</span>
                            @endif
                            @if($moment['progress']>0 && $moment['progress']<100)
                            <i class="fa fa-circle  mr-2 fs-2" style="color:#F9E538" aria-hidden="true"></i><span class="fs--1">En proceso</span> 
                            @endif 
                            @if($moment['progress']==100)
                            <i class="fa fa-circle mr-2 fs-2" style="color:#6CB249" aria-hidden="true"></i> <span class="fs--1">Concluida</span>
                            @endif
                            </span>
                        </div>
                        <div class="ml-auto d-flex">
                            <span class="font-weight-bold fs-0 mr-2" style="font-size: 14px;">
                                Última conexión:
                                @if($moment['lastAccessInMoment'] != null)
                                     {{$moment['lastAccessInMoment']}}
                                @else
                                    Sin iniciar
                                @endif
                            </span>
                            <div class="cursor-pointer" ng-show="!mbShowMoment{{$moment['id']}}" ng-click="mbShowMoment{{$moment['id']}} = !mbShowMoment{{$moment['id']}}">
                                <i class="fa fa-angle-down fs-2" aria-hidden="true"></i>
                            </div>
							<div class="cursor-pointer" ng-show="mbShowMoment{{$moment['id']}}" ng-click="mbShowMoment{{$moment['id']}} = !mbShowMoment{{$moment['id']}}">
                                <i class="fa fa-angle-up fs-2" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    
					@foreach ($moment['ratings'] as $rating)
					<div class="row mt-3 col-12 pr-0" ng-show="mbShowMoment{{$moment['id']}}">
						<div class="{{$rating['element']['class']}} evidence-head d-flex" 
						   ng-style="{@if(isset($rating['element']['color'])) 'color': '{{$rating['element']['color']}}', @endif 
						   @if(isset($rating['element']['background_color'])) 'background-color': '{{$rating['element']['background_color']}}', @endif}" 
						   style="@if(isset($rating['element']['style'])) {{$rating['element']['style']}} @endif; width:100%;height:auto;">
						   <div class="col-1-5"> 
						   @if(isset($rating['element']['icon']))
							<img src="{{asset($rating['element']['icon'])}}" width="auto" height="40px"/>
						   @else 
						   <img src="{{asset('images/icons/evidenciasAprendizajeIcono-01.png')}}" width="auto" height="40px"/>
						   @endif
						   </div>
						   <div class="col-7 col-md-6 p-0">  
							<span>Preguntas de {{$rating['element']['subtitle']}}</span>
						   </div>
						   <div class="col-3 p-0 fs-0">  
								@if(isset($rating['evidences'] ))
									@if($rating['evidences']->weighted>=90)
									<i class="fa fa-circle mr-2" style="color:#6CB249" aria-hidden="true"></i> (S) {{$rating['evidences']->weighted}} %
									@endif
									@if($rating['evidences']->weighted>=70 && $rating['evidences']->weighted<=89)
									<i class="fa fa-circle  mr-2" style="color:#6CB249" aria-hidden="true"></i> (A) {{$rating['evidences']->weighted}} %
									@endif
									@if($rating['evidences']->weighted>=60 && $rating['evidences']->weighted<=69)
									<i class="fa fa-circle mr-2" style="color:#F9E538" aria-hidden="true"></i> (B) {{$rating['evidences']->weighted}} %
									@endif
									@if($rating['evidences']->weighted>=40 && $rating['evidences']->weighted<=59)
									<i class="fa fa-circle mr-2" style="color:#AC312A" aria-hidden="true"></i> (B) {{$rating['evidences']->weighted}} %
									@endif
									@if($rating['evidences']->weighted<40)
									<i class="fa fa-circle mr-2" style="color:#AC312A" aria-hidden="true"></i> (B) {{$rating['evidences']->weighted}} %
									@endif
								@else 
									<i class="fa fa-circle mr-2" style="color:#706B66" aria-hidden="true"></i><span class="">Sin iniciar</span>
								@endif
							</div>
						   @if(isset($rating['evidences']['answers']))
						   <div class="ml-auto mr-3 fs-2">
							   <div class="cursor-pointer" ng-show="!mbShowEvidence{{$rating['element']['id']}}" ng-click="mbShowEvidence{{$rating['element']['id']}} = !mbShowEvidence{{$rating['element']['id']}}">
									<i class="fa fa-angle-down" aria-hidden="true"></i>
							   </div>
							   <div class="cursor-pointer" ng-show="mbShowEvidence{{$rating['element']['id']}}" ng-click="mbShowEvidence{{$rating['element']['id']}} = !mbShowEvidence{{$rating['element']['id']}}">
									<i class="fa fa-angle-up" aria-hidden="true"></i>
							   </div>
						   </div>
						   @endif
						 </div>
					</div>
					@if(isset($rating['evidences']['answers']))
					<div class="col-12 mt-3" ng-show="mbShowEvidence{{$rating['element']['id']}}">
						<div class="row  bg-blue rounded-sm pl-3 pb-3 pt-3 text-center font-weight-bold" style="color:white">
							<div class="col-3 p-0 border-left-white">Pregunta</div>
							<div class="col-3 p-0 border-left-white">Respuesta</div>
							<div class="col-3 p-0 border-left-white">Desempeño</div>
							<div class="col-3 p-0">Comentario</div>
						</div>
						
						
						@foreach ($rating['evidences']['answers'] as $indexA=>$answer)
						<div class="row mt-3 @if($indexA%2==0) bg-soft-dark @endif rounded-sm pl-3 pb-3 pt-3 fs--1">
							<div class="col-3 p-0 border-left-blue">{{$answer['question']['title']}}</div>
							<div class="col-3 pl-3 border-left-blue">Respuesta</div>
							<div class="col-3 p-0 border-left-blue text-center">Desempeño</div>
							<div class="col-3 pl-3">{{$answer['concept']}}</div>
						</div>
						@endforeach
					</div>
					@endif
					
					@endforeach
               </div>
            @endforeach
    
        
        </div>
    @else 
        <div class="col-12 sequences-line" ng-show="sequences.length === 0">
            <div class="oval-line mb-4"></div>
            <h6>Aún no cuentas con la guías de aprendizaje seleccionada.</h6>
        </div>
    @endif

</div>
@endsection
@section('js')
    <script src="{{asset('/../angular/controller/achievementsStudentCtrl.js')}}"></script>
@endsection




 