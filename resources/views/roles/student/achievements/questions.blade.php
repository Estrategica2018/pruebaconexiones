@extends('roles.student.achievements.layout')

@section('achievements_layout')
<div class="row p-2 pl-md-4 pr-md-3" ng-controller="achievementsStudentCtrl" ng-init="initSequences(1)" >
    @if(isset($sequence))
        <div class="col-12 mt-sm-2 pr-sm-0">
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
                <div class="d-block col-2-2 text-align  opacity-50 cursor-not-allowed">
                    <div class="col-12 border-left-mini">
                        <img src="{{asset('images/icons/reporteSecuencias.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                    </div>
                    <div class="font-weight-bold p-3 fs--2">Reporte por guía de aprendizaje</div>
                </div>
                <div class="d-block col-2-2 text-align">
                    <a href="{{
                    route('student.achievements.moment',
                    ['empresa'=>auth('afiliadoempresa')->user()->company_name(),
                     'affiliated_account_service_id'=>$affiliated_account_service_id,
                     'sequence_id'=>$sequence['id']
                    ])}}">
                        <div class="col-12 border-left-mini">
                            <img src="{{asset('images/icons/reporteMomentos.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                        </div>
                        <div class="font-weight-bold p-3 fs--2">Reporte por momento</div>
                    </a>
                </div>
                <div class="d-block col-2-2 text-align">
                    <div class="col-12 border-left-mini">
                        <img src="{{asset('images/icons/reportePreguntas.png')}}" class="imagen-reports-type-mini"  width="45px" height= "auto"/>
                    </div>
                    <div class="font-weight-bold p-3 fs--2">Reporte por preguntas</div>
                </div>
            </div>
            <h5 class="mt-3  mb-3">Reporte por guía de aprendizaje</h5>
            <div>
            @foreach ($moments as $key => $moment)
               <div class="row p-3  rounded @if($key%2==0) bg-soft-dark @else bg-soft-dark-light @endif">
                    <div class="col-8 col-md-6">
                        <span class="fs--1"> Momento {{$moment['order']}}</span>
                        <h5 class="col-6 fs--1"> Momento {{$moment['name']}}</span>
                    </div>

                    <span class="font-weight-normal col-2-5 col-md-3 fs--1"> 
                    @if($moment['advance']==0)
                    <i class="fa fa-circle mr-2" style="color:#706B66" aria-hidden="true"></i>Sin iniciar
                    @endif
                    @if($moment['advance']>0 && $moment['advance']<100)
                    <i class="fa fa-circle  mr-2" style="color:#F9E538" aria-hidden="true"></i> En proceso
                    @endif
                   
                    @if($moment['advance']==100)
                    <i class="fa fa-circle mr-2" style="color:#6CB249" aria-hidden="true"></i> Concluida
                    @endif
                    </span>
                    
                    <span class="font-weight-bold col-3 fs--1">
                        <label class="">Desempeño</label>
                        @if($moment['advance']!=0)
                            @if($moment['performance'])
                                @if($moment['performance']>=90)
                                <i class="fa fa-circle mr-2" style="color:#6CB249" aria-hidden="true"></i> (S) 
                                @endif
                                @if($moment['performance']>=70 && $moment['performance']<=89)
                                <i class="fa fa-circle  mr-2" style="color:#6CB249" aria-hidden="true"></i> (A)
                                @endif
                                @if($moment['performance']>=60 && $moment['performance']<=69)
                                <i class="fa fa-circle mr-2" style="color:#F9E538" aria-hidden="true"></i> (B)
                                @endif
                                @if($moment['performance']>=40 && $moment['performance']<=59)
                                <i class="fa fa-circle mr-2" style="color:#AC312A" aria-hidden="true"></i> (B)
                                @endif
                                @if($moment['performance']<40)
                                <i class="fa fa-circle mr-2" style="color:#AC312A" aria-hidden="true"></i> (B)
                                @endif
                                {{$moment['performance']}} %
                            @else
                                <i class="fa fa-circle mr-2" style="color:#706B66" aria-hidden="true"></i>Sin iniciar
                            @endif    
                        @else
                            <i class="fa fa-circle mr-2" style="color:#706B66" aria-hidden="true"></i>Sin iniciar
                        @endif
                    </span>

               </div>
            @endforeach
            <div>

            </div>
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
