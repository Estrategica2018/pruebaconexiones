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
            <div class="d-flex">
                <img class="imagen-sequence" 
                src="{{asset($sequence['url_image'])}}" width="80px" height= "100px"/>
                <div class="d-block col-5 mr-2 ml-2 fs--1">
                    <p class="font-weight-bold mb-1">Guía de aprendizaje</p>
                    <p class="fs-0" >{{$sequence['name']}}</p>
                </div>
                <div class="d-block col-2-2 text-align">
                    <a href="{{
                        route('student.achievements.sequence',
                        ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                            'affiliated_account_service_id'=>$affiliated_account_service_id,
                            'sequence_id'=>$sequence['id']
                        ])}}">
                        <div class="col-12 border-left-mini">
                            <img src="{{asset('images/icons/reporteSecuencias.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                        </div>
                        <div class="font-weight-bold p-3 fs--2">Reporte por guía de aprendizaje</div>
                    </a>
                </div>
                <div class="d-block col-2-2 text-align  opacity-50 cursor-not-allowed">
                    <div class="col-12 border-left-mini">
                        <img src="{{asset('images/icons/reporteMomentos.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                    </div>
                    <div class="font-weight-bold p-3 fs--2">Reporte por momento</div>
                </div>
                <div class="d-block col-2-2 text-align">
                    <div class="col-12 border-left-mini">
                        <img src="{{asset('images/icons/reportePreguntas.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                    </div>
                    <div class="font-weight-bold p-3 fs--2">Reporte por preguntas</div>
                </div>
            </div>
            <h5 class="mt-3  mb-3">Reporte por momentos</h5>
        
            @foreach ($moments as $key => $moment)
               <div class="row p-3 rounded @if($key%2==0) bg-soft-dark @else bg-soft-dark-light @endif">

                    <div class="col-12 d-flex">
                        <span class="fs-0"> Momento {{$moment['order']}} <small>{{$moment['name']}}</small></span>
                        <div class="ml-4">
                            @if($moment['advance']==0)
                            <i class="fa fa-circle mr-2 fs--1" style="color:#706B66" aria-hidden="true"></i>Sin iniciar
                            @endif
                            @if($moment['advance']>0 && $moment['advance']<100)
                            <i class="fa fa-circle  mr-2 fs--1" style="color:#F9E538" aria-hidden="true"></i> En proceso
                            @endif
                        
                            @if($moment['advance']==100)
                            <i class="fa fa-circle mr-2 fs--1" style="color:#6CB249" aria-hidden="true"></i> Concluida
                            @endif
                            </span>
                        </div>
                        <div class="ml-auto">
                            <span class="font-weight-bold fs--1">
                                Última conexión:
                                @if($moment['lastAccessInMoment'] != null)
                                     {{$moment['lastAccessInMoment']}}
                                @else
                                    Sin iniciar
                                @endif
                            </span>
                        
                        </div>
                    </div>
                    
                    
                    <div class="row mt-3 ml-auto mr-auto w-md-90 ml-auto mr-auto">
                        @foreach ($moment['sections'] as $section)
                        <div class="col-12 row p-3">
                            <div class="col-12 row border-1000 border-bottom p-0">
                                <div class="col-5 p-0 fs-0">
                                    <span class="fs--1"><strong>{{$section['name']}} : </strong> {{$section['title']}}</span>
                                </div>
                                <div class="col-3 p-0 fs--1">  
                                    <strong> Progreso: </strong>
                                    @if(isset($section['progress']))
                                        <i class="fa fa-circle mr-2" style="color:#6CB249" aria-hidden="true"></i>  
                                        {{ $section['progress']}}
                                    @else 
                                        <i class="fa fa-circle mr-2" style="color:#706B66" aria-hidden="true"></i> 
                                        Sin iniciar
                                    @endif
                                </div> 
                                <div class="col-3 p-0 fs--1">  
                                  
                                    @if(isset($section['performance'] ))
                                        <strong> Desempeño: </strong>
                                        <i class="fa fa-circle mr-2" style="color:#6CB249" aria-hidden="true"></i> 
                                        {{ $section['performance']}} {{ $section['quantity']}} % 
                                    @endif  
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
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




 